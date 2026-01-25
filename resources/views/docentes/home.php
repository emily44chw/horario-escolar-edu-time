@extends('layouts.app')

@section('content')
<h1>Bienvenido Docente</h1>
<p>Aquí puedes gestionar tus clases y horarios.</p>

<li><a href="{{ route('schedule.index') }}">Ver Horarios</a></li>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar Sesión</button>
</form>
@endsection