$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

 $(document).ready(function () {
    var assignments = [];
    const DAYS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

         // ----- Para generacion de grilla segun horario -----
        let courseType = null; // 'basica' o 'bachillerato'

        function getHoursByCourseType() {
                if (courseType === 'basica') {
                    return [7, 8, 9, 10, 11]; // hasta 12pm
                } else if (courseType === 'bachillerato') {
                    return [7, 8, 9, 10, 11, 12]; // hasta 13pm
                }
                return [];
        }
        // esto genera la tabla de acuerdo a "Asignaciones"
        function generateEmptyGrid() {
            let tbody = $('#schedule-body');
            tbody.html('');

            let hours = getHoursByCourseType();

            hours.forEach(hour => {
                let start = String(hour).padStart(2, '0') + ':00';
                let end = String(hour + 1).padStart(2, '0') + ':00';

                    // Recreo de 9 a 10am
                    if (hour === 9) {
                        let row = `<tr class="table-warning">
                                                    <td>${start}</td>
                                                    <td>${end}</td>
                                                    <td colspan="5"><strong>RECESO</strong></td>
                                                   </tr>`;
                        tbody.append(row);
                        return;
                    }

                    let row = `<tr data-start="${start}">
                                                <td>${start}</td>
                                                <td>${end}</td>`;

                    for (let d of DAYS) {
                        row += `<td data-day="${d}" class="schedule-cell"></td>`;
                    }

                    row += `</tr>`;
                    tbody.append(row);
            });
        }

        function renderScheduleGrid() {
            generateEmptyGrid();

            assignments.forEach(a => {
                let row = $(`tr[data-start="${a.start_time}"]`);
                let cell = row.find(`td[data-day="${a.day}"]`);
                    if (cell.length) {
                        cell.html(`<div><strong>${a.subject_name}</strong></div><div style="font-size:12px">${a.teacher_name}</div>`);
                    }
            });
        }

        // Seleccion de curso
        $('#course-select').change(function () {
            var courseId = $(this).val();
            let courseName = $('#course-select option:selected').text();

            if (courseName.includes('Bachillerato')) {
                    courseType = 'bachillerato';
            } else {
                    courseType = 'basica';
            }
            generateEmptyGrid();

            if (!courseId) return;

            $('#course-select').prop('disabled', true);
            $('#new-course-btn').show();
            $('#current-course-label').text('Curso: ' + $('#course-select option:selected').text()).show();

            assignments = [];
            $('#schedule-table tbody').html('');
            $('#schedule-table').hide();
            $('#save-btn').hide();

            $.get('/schedules/subjects/' + courseId, function (data) {
            console.log('Materias recibidas:', data);
            $('#subject-select').html('<option value="">-- Selecciona una asignatura --</option>');

            $.each(data, function (i, subject) {
                let teacherName = 'Sin asignar';
                let teacherId = null;

                if (subject.teachers && subject.teachers.length > 0) {
                        let t = subject.teachers[0];
                        teacherName = t.first_name + ' ' + t.last_name;
                        teacherId = t.id;
                }

                $('#subject-select').append(`<option value="${subject.id}" data-teacher="${teacherName}" data-teacher-id="${teacherId}">${subject.name}</option>`);
                });

                    $('#schedule-form').show();
                });
            });

            // Cambiar materia o dia -> cargar slots
            $('#subject-select, #day-select').change(function () {
                var courseId = $('#course-select').val();
                var subjectId = $('#subject-select').val();
                var day = $('#day-select').val();

                if (courseId && subjectId && day) {
                    $.get(`/schedules/slots?course_id=${courseId}&subject_id=${subjectId}&day=${day}`, function (data) {
                        $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                        $.each(data, function (i, slot) {
                            $('#slot-select').append(`<option value="${slot.start} - ${slot.end}">${slot.start} - ${slot.end}</option>`);
                        });

                        var teacher = $('#subject-select option:selected').data('teacher');
                        $('#teacher-display').val(teacher || 'Sin asignar');
                    });
                }
            });

            // Agregar asignacion
            $('#add-assignment-btn').click(function () {
                var courseId = $('#course-select').val();
                var subjectId = $('#subject-select').val();
                var day = $('#day-select').val();
                var slot = $('#slot-select').val();
                var teacher = $('#teacher-display').val();
                var subjectName = $('#subject-select option:selected').text();
                var teacherId = $('#subject-select option:selected').data('teacher-id');

                if (courseId && subjectId && day && slot) {
                    assignments.push({
                        subject_id: subjectId,
                        day: day,
                        start_time: slot.split(' - ')[0],
                        end_time: slot.split(' - ')[1],
                        subject_name: subjectName,
                        teacher_name: teacher,
                        teacher_id: teacherId,
                        classroom_id: null // si no tengo aulas - ajustar en db pendiente
                    });

                    renderScheduleGrid();
                    $('#schedule-table').show();
                    $('#save-btn').show();

                    // Limpiar para proxima asignacion
                    $('#subject-select').val('');
                    $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                    $('#teacher-display').val('');
                }
            });

            // Guardar horario en DB
            $('#save-btn').click(function () {
                $.post('/schedules/store', {
                    course_id: $('#course-select').val(),
                    assignments: assignments,

                }, function (data) {
                    alert(data.success);
                    assignments = [];
                    renderScheduleGrid();
                    $('#save-btn').hide();
                }).fail(function (xhr) {
                    console.log('Error guardando:', xhr.responseText);
                    alert('Error interno al guardar. Revisa los logs.');
                });
            });

        // Reset formulario - volver a mostrar campos
        $('#new-course-btn').click(function () {
            if (assignments.length > 0 && !confirm('No guardaste asignaciones, ¿descartar?')) return;

            assignments = [];
                renderScheduleGrid();
                $('#course-select').prop('disabled', false).val('');
                $('#new-course-btn').hide();
                $('#schedule-form').hide();
                $('#current-course-label').hide();
                $('#schedule-table tbody').html('');
                $('#schedule-table').hide();
                $('#save-btn').hide();
                $('#subject-select').val('');
                $('#day-select').val('Lunes');
                $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                $('#teacher-display').val('');
        });
     });