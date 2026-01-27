@extends('layouts.app') <!-- Extiende el layout con Bootstrap -->

@section('content')
    <h1>Crear Nuevo Horario</h1>
    <p>Selecciona un curso para comenzar. Si el curso ya tiene horario, no podrás crear uno nuevo.</p>

    <!-- Campo para seleccionar curso (lista desplegable) -->
    <div class="form-group">
        <div class="form-group mb-4">
            <label for="course-select">Seleccione un Curso:</label>

            <div class="d-flex gap-2">
                <select id="course-select" class="form-control" required>
                    <option value="">-- Selecciona un curso --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>

                <button id="new-course-btn" class="btn btn-warning" style="display:none; white-space: nowrap;">
                    Otro registro
                </button>
            </div>
        </div>
    </div>


    <!-- Contenedor para el resto del formulario (se muestra después de verificar curso) -->
    <div id="schedule-form" style="display:none;">
        <div class="row g-3">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="subject-select">Nombre de Materia:</label>
                    <select id="subject-select" class="form-control" required>
                        <option value="">-- Seleccione una asignatura --</option>
                    </select>
                </div>


                <!-- Selección de día -->
                <div class="form-group mb-3">
                    <label for="day-select">Selecciona el día:</label>
                    <select id="day-select" class="form-control" required>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                    </select>
                </div>
            </div>

            <!-- Lista de horarios disponibles (slots, cargados vía AJAX) -->
            <!-- Columna derecha -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="slot-select">Horarios Disponibles:</label>
                    <select id="slot-select" class="form-control" required>
                        <option value="">-- Selecciona un horario --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="teacher-display">Profesor Asignado:</label>
                    <input type="text" id="teacher-display" class="form-control" readonly>
                </div>

                <small class="form-text text-muted">
                    Solo se muestran horarios con profesores disponibles.
                </small>
            </div>
        </div>
    </div>

    <!-- Botón para agregar asignación (guarda en la tabla) -->
    <div class="text-center mt-3">
        <button type="button" id="add-assignment-btn" class="btn btn-primary">
            Agregar asignación
        </button>
    </div>

    <div class="mt-4">
        <!-- Tabla "Horario Seleccionado" (se actualiza dinámicamente) -->
        <h2 class="mt-5 mb-4">Horario Seleccionado</h2>
        <!-- Muestra nombre de curso selecionado - trabajo actual -->
        <p id="current-course-label" class="font-weight-bold mb-3 text-center" style="display:none;"></p>


        <table id="schedule-table" class="table table-bordered text-center" style="display:none;">
            <thead>
                <tr>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
            </thead>
            <tbody id="schedule-body">
                <!-- Se llena por JS -->
            </tbody>
        </table>

    </div>

    <!-- Boton para guardar horario -->
    <div class="text-center mt-4">
        <button id="save-btn" class="btn btn-success" style="display:none;">
            Guardar Horario
        </button>
    </div>
    <p id="status-message" style="display:none;"></p> <!-- Mensaje de status (completed/pending) -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var assignments = []; // asignaciones actuales
            var lockedCourseId = null; // curso actualmente en edicion
            const DAYS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

            function generateEmptyGrid() {
                let tbody = $('#schedule-body');
                tbody.html('');

                let startHour = 7;
                let endHour = 13; // luego lo hacemos dinámico

                for (let hour = startHour; hour < endHour; hour++) {
                    let start = String(hour).padStart(2, '0') + ':00';
                    let end = String(hour + 1).padStart(2, '0') + ':00';

                    let row = `<tr data-start="${start}">`;
                    row += `<td>${start}</td>`;
                    row += `<td>${end}</td>`;

                    for (let d of DAYS) {
                        row += `<td data-day="${d}" class="schedule-cell"></td>`;
                    }

                    row += `</tr>`;
                    tbody.append(row);
                }
            }

            generateEmptyGrid();

            function renderScheduleGrid() {
                generateEmptyGrid(); // primero limpia y recrea la grilla

                assignments.forEach(a => {
                    let row = $(`tr[data-start="${a.start_time}"]`);
                    let cell = row.find(`td[data-day="${a.day}"]`);

                    if (cell.length) {
                        cell.html(`
                                                <div><strong>${a.subject_name}</strong></div>
                                                                                                        <div style="font-size:12px">${a.teacher_name}</div>
                                                                                                    `);
                    }
                });
            }


            // Al seleccionar curso, verificar si ya tiene horario y cargar asignaturas
            $('#course-select').change(function () {
                var courseId = $(this).val();
                var courseName = $('#course-select option:selected').text();

                if (!courseId) return;

                // Bloqueamos el selector de curso
                $('#course-select').prop('disabled', true);
                $('#new-course-btn').show();

                lockedCourseId = courseId;

                // Mostramos el nombre del curso en la sección de horario
                $('#current-course-label')
                    .text('Curso: ' + courseName)
                    .show();

                // Limpiamos cualquier basura anterior
                assignments = [];
                $('#schedule-table tbody').html('');
                $('#schedule-table').hide();
                $('#save-btn').hide();

                // Cargamos materias
                $.get('/schedules/subjects/' + courseId, function (data) {
                    $('#subject-select').html('<option value="">-- Selecciona una asignatura --</option>');

                    $.each(data, function (key, subject) {
                        let teacherName = 'Sin asignar';
                        let teacherId = null;

                        if (subject.teachers && subject.teachers.length > 0) {
                            let t = subject.teachers[0];
                            teacherName = t.first_name + ' ' + t.last_name;
                            teacherId = t.id;
                        }

                        $('#subject-select').append(
                            `<option value="${subject.id}" data-teacher="${teacherName}" data-teacher-id="${teacherId}">
                                                                                                                                                                                                                            ${subject.name}
                                                                                                                                                                                                                        </option>`
                        );
                    });

                    $('#schedule-form').show();
                });
            });

            // Al cambiar asignatura o día, cargar slots disponibles
            $('#subject-select, #day-select').change(function () {
                var courseId = $('#course-select').val();
                var subjectId = $('#subject-select').val();
                var day = $('#day-select').val();
                console.log('Parámetros enviados:', { courseId, subjectId, day });
                if (courseId && subjectId && day) {
                    $.get('/schedules/slots?course_id=' + courseId + '&subject_id=' + subjectId + '&day=' + day, function (data) {
                        console.log('Respuesta de slots:', data);

                        $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                        $.each(data, function (key, slot) {
                            $('#slot-select').append(
                                '<option value="' + slot.start + ' - ' + slot.end + '">' + slot.start + ' - ' + slot.end + '</option>'
                            );
                        });

                        var teacher = $('#subject-select option:selected').data('teacher');
                        $('#teacher-display').val(teacher || 'Sin asignar');
                    }).fail(function (xhr, status, error) {
                        console.log('Error en AJAX slots:', status, error);
                        alert('Error interno al cargar slots. Revisa los logs.');
                    });

                } else {
                    console.log('Faltan parámetros');
                }
            });

            // Agregar asignación a la tabla y array
            $('#add-assignment-btn').click(function () {
                var courseId = $('#course-select').val();
                var subjectId = $('#subject-select').val();
                var day = $('#day-select').val();
                var slot = $('#slot-select').val();
                var teacher = $('#teacher-display').val();
                var subjectName = $('#subject-select option:selected').text();
                var teacherId = $('#subject-select option:selected').data('teacher-id');

                if (courseId && subjectId && day && slot) {
                    // Agregar a array (para guardar)
                    assignments.push({
                        subject_id: subjectId,
                        day: day,
                        start_time: slot.split(' - ')[0], // Ej. "07:00"
                        end_time: slot.split(' - ')[1],
                        subject_name: subjectName,
                        teacher_name: teacher
                    });

                    // Renderiza la grilla correctamente
                    renderScheduleGrid();

                    // Muestra tabla y botón de guardar
                    $('#schedule-table').show();
                    $('#save-btn').show();
                }

                // Limpiar formulario para nueva asignación
                $('#subject-select').val('');
                $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                $('#teacher-display').val('');
            });

            // Guardar horario (envía assignments y verifica huecos)
            $('#save-btn').click(function () {
                var courseId = $('#course-select').val();
                $.post('/schedules/store', {
                    course_id: courseId,
                    assignments: assignments,
                    _token: '{{ csrf_token() }}' // Token CSRF para Laravel
                }, function (data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        $('#status-message').text('Horario guardado. Status: ' + data.success.split(': ')[1]).show();
                        // Recargar tabla si es necesario
                        $.get('/schedules/selected/' + courseId, function (data) {
                            // Actualizar tabla con datos del servidor si quieres
                        });
                    }
                }).fail(function (xhr, status, error) {
                    console.log('Error guardando:', status, error);
                    alert('Error interno al guardar. Revisa los logs.');
                });
            });

            $('#new-course-btn').click(function () {

                if (assignments.length > 0) {
                    var confirmDiscard = confirm('Usted no ha guardado la creación anterior, ¿desea descartar las asignaciones?');

                    if (!confirmDiscard) {
                        return; // NO hace nada
                    }
                }

                // Reset total
                assignments = [];
                renderScheduleGrid();
                lockedCourseId = null;

                $('#course-select').prop('disabled', false).val('');
                $('#new-course-btn').hide();

                $('#schedule-form').hide();
                $('#current-course-label').hide();

                $('#schedule-table tbody').html('');
                $('#schedule-table').hide();
                $('#save-btn').hide();

                // Limpia formulario
                $('#subject-select').val('');
                $('#day-select').val('Lunes');
                $('#slot-select').html('<option value="">-- Selecciona un horario --</option>');
                $('#teacher-display').val('');
            });
        });
    </script>
@endsection