@extends('layouts.app')

@section('title', 'Создать модуль')

@section('content')

<a href="{{ route('teacher.courses.edit', $course->id) }}" class="btn btn-link mb-3">← Назад к курсу</a>

<h3 class="mb-3">Создать модуль для курса: {{ $course->title }}</h3>

<div class="card p-3">

    <form method="POST" action="{{ route('teacher.modules.store', $course->id) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Название модуля</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <button class="btn btn-primary">Создать</button>
    </form>

</div>

@endsection
