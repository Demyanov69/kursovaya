@extends('layouts.app')

@section('title', 'Редактировать модуль')

@section('content')

    <a href="{{ route('teacher.courses.edit', $module->course->id) }}" class="btn btn-link mb-3">← Назад к курсу</a>

    <h3 class="mb-3">Редактирование модуля</h3>

    <div class="card p-3">

        <form method="POST" action="{{ route('teacher.modules.update', $module->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Название модуля</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $module->title) }}" required>
            </div>

            <button class="btn btn-primary">Сохранить изменения</button>
        </form>

        <hr>

        {{-- Удаление модуля --}}
        <form method="POST" action="{{ route('teacher.modules.delete', $module->id) }}"
            onsubmit="return confirm('Удалить модуль? Все уроки внутри также будут удалены.')">

            @csrf
            @method('DELETE')

            <button class="btn btn-danger">
                Удалить модуль
            </button>
        </form>

    </div>

@endsection