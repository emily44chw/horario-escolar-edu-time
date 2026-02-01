@extends('layouts.admin') <!-- Extiende el layout con Bootstrap -->

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


                <!-- Seleccion de día -->
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

    <!-- Botón para agregar asignacion (guarda en la tabla) -->
    <div class="text-center mt-3">
        <button type="button" id="add-assignment-btn" class="btn btn-primary">
            Agregar asignación
        </button>
    </div>

    <div class="mt-4">
        <!-- Tabla "Horario Seleccionado" (se actualiza dinamicamente) -->
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
    <script src="{{ asset('js/schedule-create.js') }}"></script>

@endsection