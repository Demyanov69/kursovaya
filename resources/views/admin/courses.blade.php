@extends('layouts.app')

@section('title', isset($courses) ? 'Курсы' : 'Редактирование курса')

@section('content')

@if(isset($courses))
    {{-- Режим списка курсов --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Управление курсами</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($courses->isEmpty())
        <div class="alert alert-info">Курсы не найдены.</div>
    @else
        <div class="card p-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Уроков</th>
                        <th width="150">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->author->name ?? 'Не указан' }}</td>
                        <td>{{ $course->category->name ?? 'Без категории' }}</td>
                        <td>{{ $course->lessons_count }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.courses.edit', $course->id) }}" 
                                   class="btn btn-primary btn-sm">Редактировать</a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Удалить курс?')">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@else
    {{-- Режим редактирования курса --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Редактирование курса: {{ $course->title }}</h3>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
            ← Назад к списку
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Название курса</label>
                <input type="text" name="title" class="form-control" 
                       value="{{ old('title', $course->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Категория</label>
                <select name="category_id" class="form-select">
                    <option value="">Без категории</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                            {{ $course->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Преподаватель</label>
                <select name="author_id" class="form-select">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ $course->author_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} ({{ $teacher->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Факультет</label>
                <input type="text" name="faculty" class="form-control" 
                       value="{{ old('faculty', $course->faculty) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Направление</label>
                <input type="text" name="direction" class="form-control" 
                       value="{{ old('direction', $course->direction) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Год обучения</label>
                <input type="number" name="course_year" class="form-control" 
                       value="{{ old('course_year', $course->course_year) }}">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endif

@endsection