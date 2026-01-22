@extends('layouts.app') <!-- Asume un layout con Bootstrap -->

@section('content')
    <h1>Crear Horario</h1>
    <button id="create-btn">Crear Nuevo Horario</button>
    <div id="form-container" style="display:none;">
        <select id="course-select">
            <option value="">Selecciona un curso</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
        </select>
        <!-- Aquí agregar campos para materia, día, slot, etc., con JS para cargar listas -->
        <div id="schedule-table"><!-- Tabla para "Horario seleccionado" --></div>
        <button id="save-btn">Guardar</button>
    </div>

    <script>
        // JS para manejar selecciones, AJAX, y mostrar tabla (usa jQuery o vanilla JS)
        // Ejemplo: Al seleccionar curso, cargar materias; al asignar, actualizar tabla.
    </script>
@endsection