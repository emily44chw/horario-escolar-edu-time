@extends('layouts.admin')

@section('content')
    <h1>Horario: {{ $course->name }}</h1>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.horarios.list') }}" class="btn btn-secondary">
            ← Volver a Horarios
        </a>
    </div>

    @php
        $daysDisplay = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $daysDb = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];

        $courseType = $course->type ?? 'basica';
        $hours = $courseType === 'basica'
            ? ['07:00', '08:00', '09:00', '10:00', '11:00']
            : ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00'];
    @endphp

    <table class="table table-bordered text-center table-striped">
        <thead class="table-dark">
            <tr>
                <th>Hora</th>
                @foreach($daysDisplay as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($hours as $hour)
                @php
                    $start = \Carbon\Carbon::createFromFormat('H:i', $hour);
                    $endHour = $start->copy()->addHour()->format('H:i');
                @endphp

                @if($hour === '09:00')
                    <tr class="table-warning text-center">
                        <td>{{ $hour }} - {{ $endHour }}</td>
                        <td colspan="{{ count($daysDisplay) }}"><strong>RECESO</strong></td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $hour }} - {{ $endHour }}</td>
                        @foreach($daysDb as $index => $dayDb)
                            @php
                                $match = $schedules->first(function ($s) use ($dayDb, $hour) {
                                    return $s->day === $dayDb
                                        && \Carbon\Carbon::parse($s->start_time)->format('H:i') === $hour;
                                });
                            @endphp
                            <td class="align-middle">
                                @if($match)
                                    <div><strong>{{ $match->subject->name }}</strong></div>
                                    <div style="font-size:12px; color:#555;">
                                        {{ $match->teacher?->first_name }} {{ $match->teacher?->last_name }}
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endsection