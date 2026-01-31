@extends('layouts.admin')

@section('content')

    <div class="page-header">
        <h1>Gestionar Cursos</h1>

        <div class="page-actions">
            <form method="GET" action="{{ route('admin.cursos.index') }}">
                <input type="text" id="searchCurso" name="search" placeholder="Buscar por el nombre del curso"
                    class="search-input">
            </form>

            <a href="{{ route('admin.cursos.create') }}" class="btn-primary">
                Crear Curso +
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Asignar estudiante</th>
                <th>Materias</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($courses as $course)
                <tr>
                    {{-- Curso --}}
                    <td>
                        <strong>{{ $course->name }}</strong>
                    </td>

                    {{-- Asignar estudiante --}}
                    <td>
                        <a href="{{ route('admin.cursos.assignEstudiantes', $course->id) }}" class="btn-assign">
                            Asignar estudiantes
                        </a>
                    </td>


                    {{-- Asignar materias --}}
                    <td>
                        <a href="{{ route('admin.cursos.assignMaterias', $course->id) }}" class="btn-assign">
                            Asignar materias
                        </a>
                    </td>

                    {{-- Acciones --}}
                    <td class="actions">
                        <a href="{{ route('admin.cursos.show', $course) }}" class="icon-btn view" title="Ver">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('admin.cursos.edit', $course) }}" class="icon-btn edit" title="Editar">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('admin.cursos.destroy', $course) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button class="icon-btn delete" title="Eliminar" onclick="return confirm('¿Eliminar este curso?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    @if(session('success'))
        <script>alert('{{ session('success') }}');</script>
    @endif

    @if(session('error'))
        <script>alert('{{ session('error') }}');</script>
    @endif
@endsection