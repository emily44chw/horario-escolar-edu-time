@extends('layouts.student')

@section('content')

    <h2 class="agenda-title">
        <i class="fas fa-calendar-alt"></i> Mi horario semanal
    </h2>
    <div class="calendar-wrapper">

        <!-- COLUMNA HORAS -->
        <div class="time-column">
            <div class="time-header">Hora</div>

            @for($h = 7; $h <= 13; $h++)
                <div class="time-slot">
                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                </div>
            @endfor
        </div>

        <!-- DIAS -->
        @php
            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        @endphp
        <div class="days-grid">
            {{-- RECREO --}}
            <div class="recess-row" style="top: 180px; height: 60px;">
                <i class="fas fa-coffee"></i> RECREO
            </div>

            @foreach($days as $day)
                <div class="day-column">
                    <div class="day-header">{{ $day }}</div>

                    <div class="day-body">
                        @if(isset($schedulesByDay[$day]))
                            @foreach($schedules->where('day', $day) as $schedule)
                                @php
                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                    $end = \Carbon\Carbon::parse($schedule->end_time);
                                    $top = (($start->hour - 7) * 60) + $start->minute;
                                    $height = $end->diffInMinutes($start);
                                @endphp

                                <div class="event" style="top: {{ $top }}px; height: {{ $height }}px;">
                                    <strong>{{ $schedule->subject->name }}</strong>
                                    <small>
                                        {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                    </small>
                                    <span>{{ optional($schedule->teacher->user)->name ?? 'Sin profesor asignado' }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection