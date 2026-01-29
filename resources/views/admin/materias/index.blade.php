@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Materias</h1>
        <input type="text" id="searchMateria" placeholder="Buscar Materia..." onkeyup="filterTable()">
        <a href="{{ route('admin.materias.create') }}" class="btn-primary">Crear Nueva Materia</a>
    </div>

    <table class="data-table" id="materiasTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->id }}</td>
                    <td class="materia-nombre">{{ $subject->name }}</td>
                    <td>
                        <a href="{{ route('admin.materias.edit', $subject->id) }}" class="btn btn-sm btn-warning">
                            Editar
                        </a>
                        <form action="{{ route('admin.materias.destroy', $subject->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta materia?');">
                                Eliminar
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