@extends('layouts.app')

@section('title', isset($term) ? 'Редактировать термин' : 'Создать термин')

@section('content')

<h3 class="mb-3">
    @isset($term)
        Редактирование термина
    @else
        Новый термин
    @endisset
</h3>

<div class="card p-4">

    <form method="POST"
        action="{{ isset($term)
            ? route('teacher.glossary.update', $term->id)
            : route('teacher.glossary.store', $course->id) }}">

        @csrf

        @isset($term)
            @method('PUT')
        @endisset

        <div class="mb-3">
            <label class="form-label">Термин</label>
            <input type="text"
                   name="term"
                   class="form-control"
                   value="{{ old('term', $term->term ?? '') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Краткое определение</label>
            <input type="text"
                   name="short_definition"
                   class="form-control"
                   value="{{ old('short_definition', $term->short_definition ?? '') }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Полное определение</label>
            <textarea name="full_definition"
                      rows="6"
                      class="form-control"
                      required>{{ old('definition', $term->full_definition ?? '') }}</textarea>
        </div>

        <button class="btn btn-primary">
            Сохранить
        </button>

        <a href="{{ route('teacher.glossary.index', $course->id) }}"
           class="btn btn-outline-secondary">
            Назад
        </a>

    </form>

</div>

@endsection