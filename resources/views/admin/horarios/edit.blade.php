@extends('layouts.admin')

@section('content')
    <h1>Editar Horario: {{ $course->name }}</h1>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.horarios.list') }}" class="btn btn-secondary">
            ← Volver a Horarios
        </a>
    </div>

    @php
        $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $courseType = $course->type ?? 'basica';
        $hours = $courseType === 'basica'
            ? ['07:00', '08:00', '09:00', '10:00', '11:00']
            : ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00'];
    @endphp

    <form action="{{ route('schedules.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Hora</th>
                    @foreach($days as $day)
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

                    {{-- Receso a las 09:00 --}}
                    @if($hour == '09:00')
                        <tr class="table-warning">
                            <td>{{ $hour }} - {{ $endHour }}</td>
                            <td colspan="{{ count($days) }}"><strong>RECESO</strong></td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $hour }} - {{ $endHour }}</td>
                            @foreach($days as $day)
                                @php
                                    // Buscar asignación existente
                                    $match = $schedules->first(function ($s) use ($day, $hour) {
                                        return $s->day === $day
                                            && \Carbon\Carbon::parse($s->start_time)->format('H:i') === $hour;
                                    });
                                @endphp
                                <td>
                                    <select name="assignments[{{ $day }}][{{ $hour }}]" class="form-select">
                                        <option value="">-- Vacío --</option>
                                        @foreach($course->subjects as $subject)
                                            @php
                                                $optionValue = json_encode([
                                                    'subject_id' => $subject->id,
                                                    'teacher_id' => $subject->teachers->first()->id ?? null,
                                                    'day' => $day,
                                                    'start_time' => $hour,
                                                    'end_time' => $endHour
                                                ]);
                                            @endphp
                                            <option value="{{ $optionValue }}" @if($match && $match->subject_id == $subject->id) selected
                                            @endif>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
@endsection