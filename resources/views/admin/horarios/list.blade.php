@extends('layouts.app')

@section('content')
    <h1>Horarios Creados</h1>
    @foreach($schedules as $courseId => $courseSchedules)
        <h2>Curso: {{ $courseSchedules->first()->course->name }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Asignatura</th>
                    <th>Profesor</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courseSchedules->groupBy('day') as $day => $daySchedules)
                    @foreach($daySchedules as $schedule)
                        <tr>
                            <td>{{ $day }}</td>
                            <td>{{ $schedule->subject->name }}</td>
                            <td>{{ $schedule->teacher->name }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
@endsection