@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Gestionar Estudiantes</h1>
        <div class="page-actions">
            <form method="GET" action="{{ route('admin.estudiantes.index') }}">
                <input type="text" id="searchEstudiante" placeholder="Buscar por el nombre del estudiante"
                    class="search-input">
            </form>
            <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary">
                Crear Nuevo Estudiante +
            </a>
        </div>
        <table class="data-table">
            <thead>
                <br>
                <tr>
                    <th>Nombres y Apellidos</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tel. Representante</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="estudiantesTable">
                @foreach($estudiantes as $estudiante)
                    <tr>
                        <td class="estudiante-nombre">{{ $estudiante->first_name }} {{ $estudiante->last_name }}</td>
                        <td>{{ $estudiante->user->email }}</td>
                        <td>{{ $estudiante->phone }}</td>
                        <td>{{ $estudiante->representative_phone }}</td>
                        <td><span class="status active">{{ $estudiante->status }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.estudiantes.show', $estudiante) }}"><i class="fa-solid fa-user"></i></a>
                            <a href="{{ route('admin.estudiantes.edit', $estudiante) }}"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.estudiantes.destroy', $estudiante) }}" method="POST"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este estudiante?')"> <i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script>
            document.getElementById('searchEstudiante').addEventListener('keyup', function () {
                let filtro = this.value.toLowerCase();
                let filas = document.querySelectorAll('#estudiantesTable tr');

                filas.forEach(function (fila) {
                    let nombre = fila.querySelector('.estudiante-nombre').textContent.toLowerCase();
                    fila.style.display = nombre.includes(filtro) ? '' : 'none';
                });
            });
        </script>
@endsection