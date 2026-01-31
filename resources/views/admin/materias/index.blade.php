@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Materias</h1>

        <div class="page-actions">
            <input type="text" id="searchMateria" placeholder="Buscar por el nombre de la materia" class="search-input"
                onkeyup="filterTable()">

            <a href="{{ route('admin.materias.create') }}" class="btn-primary">
                Crear nueva materia +
            </a>
        </div>
    </div>

    <table class="data-table" id="materiasTable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Asignación de Docentes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
                <tr>
                    <td class="materia-nombre">{{ $subject->name }}</td>
                    <td>
                        <div class="assign-center">
                            <a href="{{ route('admin.materias.assignDocentes', $subject->id) }}" class="btn-assign">
                                Asignar Docentes
                            </a>
                        </div>
                    </td>

                    <td class="actions">
                        <a href="{{ route('admin.materias.show', $subject->id) }}" title="Ver Detalles">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('admin.materias.edit', $subject->id) }}" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('admin.materias.destroy', $subject->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta materia?');">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function filterTable() {
            let filtro = document.getElementById('searchMateria').value.toLowerCase();
            let filas = document.querySelectorAll('#materiasTable tbody tr');

            filas.forEach(function (fila) {
                let nombre = fila.querySelector('.materia-nombre').textContent.toLowerCase();
                fila.style.display = nombre.includes(filtro) ? '' : 'none';
            });
        }
    </script>
@endsection

@section('scripts')
    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
@endsection