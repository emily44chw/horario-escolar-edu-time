<h1>Bienvenido Admin</h1>
<a href="{{ route('admin.docentes.index') }}">Gestionar Docentes</a>
<a href="{{ route('admin.estudiantes.index') }}">Gestionar Estudiantes</a>
<form action="{{ route('logout') }}" method="POST">@csrf<button>Cerrar Sesión</button></form>