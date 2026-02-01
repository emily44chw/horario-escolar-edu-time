@extends('layouts.student')

@section('content')

    @foreach($courses as $course)
        @php
            $gradeName = $course->grade;

            $level = str_starts_with($gradeName, 'Bachillerato')
                ? 'Bachillerato'
                : 'Educación General Básica';

            $subjects = $course->subjects;
        @endphp

        <!-- CURSO -->
        <div class="course-header">
            <div class="course-meta">
                <div>
                    <span class="label">Nivel:</span>
                    <span class="value">{{ $level }}</span>
                </div>

                <div>
                    <span class="label">Paralelo:</span>
                    <span class="value">{{ $course->parallel }}</span>
                </div>
            </div>

            <h2 class="course-title">
                {{ $course->grade }}
            </h2>

            <p class="period">
                Periodo lectivo {{ $course->school_year }}
            </p>

            <div class="subject-count">
                <i class="fas fa-book"></i> {{ $subjects->count() }} materias asignadas
            </div>
        </div>


        <!-- MATERIAS -->
        <div class="subjects-grid">
            @forelse($course->subjects as $subject)
                @php
                    $colors = ['blue', 'green', 'purple', 'orange', 'red', 'teal', 'pink', 'indigo', 'yellow', 'cyan', 'lime', 'rose'];
                    $index = crc32($subject->name) % count($colors);
                    $colorClass = $colors[$index];
                @endphp

                <div class="subject-card {{ $colorClass }}">
                    <div class="subject-name">
                        {{ $subject->name }}
                    </div>

                    <div class="subject-teacher">
                        <i class="fas fa-user"></i>
                        {{ optional($subject->teachers->first()?->user)->name ?? 'Docente no asignado' }}
                    </div>
                </div>
            @empty
                <p class="no-subjects">Este curso no tiene materias asignadas.</p>
            @endforelse

        </div>

    @endforeach

@endsection