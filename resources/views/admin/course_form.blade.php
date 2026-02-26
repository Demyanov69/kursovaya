@extends('layouts.app')

@section('title', isset($course) ? 'Редактирование курса' : 'Создание курса')

@section('content')

<h3 class="mb-3">
    {{ isset($course) ? 'Редактирование курса: ' . $course->title : 'Создание курса' }}
</h3>

<div class="card p-3">

    <form method="POST" 
          action="{{ isset($course) ? route('admin.courses.update', $course->id) : route('admin.courses.store') }}">
        @csrf
        @if(isset($course))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Название *</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title', $course->title ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $course->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Категория</label>
            <select name="category_id" class="form-select">
                <option value="">Без категории</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $course->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Автор</label>
            <select name="author_id" class="form-select">
                <option value="">Без автора</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"
                        {{ old('author_id', $course->author_id ?? '') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Факультет</label>
            <input type="text" name="faculty" class="form-control" 
                   value="{{ old('faculty', $course->faculty ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Направление</label>
            <input type="text" name="direction" class="form-control" 
                   value="{{ old('direction', $course->direction ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Год курса</label>
            <input type="number" name="course_year" class="form-control" min="1" max="5"
                   value="{{ old('course_year', $course->course_year ?? '') }}">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                {{ isset($course) ? 'Обновить' : 'Создать' }}
            </button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Отмена</a>
        </div>
    </form>

</div>

@endsection