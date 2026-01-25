@extends('layouts.app')

@section('content')
<h1>Bienvenido Estudiante</h1>
<p>Aquí puedes ver tus horarios y clases.</p>
<li><a href="{{ route('schedule.index') }}">Ver Horarios</a></li>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar Sesión</button>
</form>
@endsection