@extends('layouts.student')

@section('content')
    <h3 class="mb-4">Mis Horarios</h3>

    @if($schedules->isEmpty())
        <div class="alert alert-info">
            Aún no tienes horarios asignados.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Día</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ $schedule->subject->name }}</td>
                            <td>{{ $schedule->teacher->name ?? '—' }}</td>
                            <td>
                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection