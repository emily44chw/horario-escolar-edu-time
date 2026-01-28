@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Gestión Docentes</h1>

        <div class="page-actions">
            <form method="GET" action="{{ route('admin.docentes.index') }}">
                <input type="text" id="searchDocente" placeholder="Buscar por el nombre del docente" class="search-input">
            </form>

            <a href="{{ route('admin.docentes.create') }}" class="btn-primary">
                Crear nuevo docente +
            </a>
        </div>

    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombres y Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="docentesTable">
            @foreach($docentes as $docente)
                <tr>
                    <td>{{ $docente->id }}</td>
                    <td class="docente-nombre">{{ $docente->first_name }} {{ $docente->last_name }}</td>
                    <td>{{ $docente->user ? $docente->user->email : 'Sin email asignado' }}</td>
                    <td>{{ $docente->phone }}</td>
                    <td>
                        <span class="status active">Activo</span>
                    </td>
                    <td class="actions">
                        <a href="{{ route('admin.docentes.show', $docente) }}" title="Ver">
                            <i class="fa-solid fa-user"></i>
                        </a>

                        <a href="{{ route('admin.docentes.edit', $docente) }}" title="Editar">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('admin.docentes.destroy', $docente) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar docente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        document.getElementById('searchDocente').addEventListener('keyup', function () {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('#docentesTable tr');

            filas.forEach(function (fila) {
                let nombre = fila.querySelector('.docente-nombre').textContent.toLowerCase();
                fila.style.display = nombre.includes(filtro) ? '' : 'none';
            });
        });
    </script>
@endsection