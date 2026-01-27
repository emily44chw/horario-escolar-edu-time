@extends('layouts.app') <!-- Extiende el layout con Bootstrap -->

@section('content')
    <h1>Crear Nuevo Horario</h1>
    <p>Selecciona un curso para comenzar. Si el curso ya tiene horario, no podrás crear uno nuevo.</p>

    <!-- Campo para seleccionar curso (lista desplegable) -->
    <div class="form-group">
        <label for="course-select">Selecciona un Curso:</label>
        <select id="course-select" class="form-control" required>
            <option value="">-- Selecciona un curso --</option>
            @if(isset($courses))
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <!-- Contenedor para el resto del formulario (se muestra después de verificar curso) -->
    <div id="schedule-form" style="display:none;">
        <!-- Lista desplegable de asignaturas (se carga vía AJAX) -->
        <div class="form-group">
            <label for="subject-select">Nombre de Materia:</label>
            <select id="subject-select" class="form-control" required>
                <option value="">-- Selecciona una asignatura --</option>
            </select>
        </div>

        <!-- Selección de día -->
        <div class="form-group">
            <label for="day-select">Selecciona un Día:</label>
            <select id="day-select" class="form-control" required>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
            </select>
        </div>

        <!-- Lista de horarios disponibles (slots, cargados vía AJAX) -->
        <div class="form-group">
            <label for="slot-select">Horarios Disponibles a Dictar la Materia:</label>
            <select id="slot-select" class="form-control" required>
                <option value="">-- Selecciona un horario --</option>
            </select>
            <small class="form-text text-muted">Recuerda: Si un profesor da clases a las 7am en otro curso, no estará
                disponible aquí.</small>
        </div>

        <!-- Campo para profesor (asociado a la asignatura, cargado automáticamente) -->
        <div class="form-group">
            <label for="teacher-display">Profesor Asignado:</label>
            <input type="text" id="teacher-display" class="form-control" readonly>
        </div>

        <!-- Botón para agregar asignación (guarda en la tabla) -->
        <button type="button" id="add-assignment-btn" class="btn btn-secondary">Agregar Asignación</button>
        <small class="form-text text-muted">Si la materia dura más de 1 hora, agrégala múltiples veces (ej. 2 veces para 2
            horas).</small>

        <!-- Tabla "Horario Seleccionado" (se actualiza dinámicamente) -->
        <h2 style="margin-top: 30px;">Horario Seleccionado</h2>
        <table id="schedule-table" class="table table-bordered" style="display:none;">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Asignatura</th>
                    <th>Profesor</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas agregadas vía JS -->
            </tbody>
        </table>

        <!-- Botón para guardar horario -->
        <button id="save-btn" class="btn btn-success" style="display:none; margin-top: 20px;">Guardar Horario</button>
        <p id="status-message" style="display:none;"></p> <!-- Mensaje de status (completed/pending) -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            var assignments = []; // Array para almacenar asignaciones antes de guardar

            // Al seleccionar curso, verificar si ya tiene horario y cargar asignaturas
            $('#course-select').change(function () {
                var courseId = $(this).val();
                if (courseId) {
                    // Verificar si el curso ya tiene horario (AJAX a una nueva ruta o lógica en store)
                    // Por ahora, asumimos que se verifica al guardar; aquí solo cargamos asignaturas
                    $.get('/schedules/subjects/' + courseId, function (data) {
                        $('#subject-select').html('<option value="">-- Selecciona una asignatura --</option>');
                        $.each(data, function (key, subject) {
                            var teacherName = subject.teachers && subject.teachers.length > 0 ? subject.teachers[0].name : 'Sin asignar';
                            var teacherId = subject.teachers && subject.teachers.length > 0 ? subject.teachers[0].id : null;
                            $('#subject-select').append('<option value="' + subject.id + '" data-teacher="' + teacherName + '" data-teacher-id="' + teacherId + '">' + subject.name + '</option>');
                        });
                        $('#schedule-form').show();
                    }).fail(function (xhr, status, error) {
                        console.log('Error cargando asignaturas:', status, error);
                        alert('Error interno al cargar asignaturas. Revisa los logs.');
                    });
                }
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
                            $('#slot-select').append('<option value="' + slot.start + '">' + slot.start + ' - ' + slot.end + '</option>');
                        });
                        // Mostrar profesor
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
                        teacher_id: teacherId
                    });
                    // Agregar a tabla
                    var endTime = slot.split(' - ')[1];
                    $('#schedule-table tbody').append('<tr><td>' + day + '</td><td>' + subjectName + '</td><td>' + teacher + '</td><td>' + slot.split(' - ')[0] + '</td><td>' + endTime + '</td></tr>');
                    $('#schedule-table').show();
                    $('#save-btn').show();
                }
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
        });
    </script>
@endsection