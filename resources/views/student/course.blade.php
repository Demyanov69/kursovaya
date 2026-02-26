@extends('layouts.app')

@section('title', $course->title)

@section('content')

<h3 class="mb-3">{{ $course->title }}</h3>

<p>{{ $course->description }}</p>

<hr>

<h5 class="mt-4 mb-3">Модули курса</h5>

@if($course->modules->isEmpty())
    <div class="alert alert-info">Модули пока не добавлены.</div>
@else
    @foreach($course->modules as $module)
    <div class="card mb-3 p-3">

        <h6 class="fw-bold">Модуль: {{ $module->title }}</h6>

        @if($module->lessons->isEmpty())
            <p class="text-muted mb-0">Уроков пока нет.</p>
        @else
            <ul class="mt-2">
                @foreach($module->lessons as $lesson)
                <li>
                    <a href="{{ route('student.lessons.show', $lesson->id) }}">
                        {{ $lesson->title }}
                    </a>
                </li>
                @endforeach
            </ul>
        @endif

    </div>
    @endforeach
@endif

@endsection
