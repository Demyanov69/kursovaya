@extends('layouts.app')

@section('title')
    @if(isset($lesson)) Редактировать урок @else Создать урок @endif
@endsection

@section('content')

    @php
        // если редактирование — у нас есть $lesson, иначе у нас есть $module
        $isEdit = isset($lesson);
    @endphp

    <a href="{{ route('teacher.courses.edit', $isEdit ? $lesson->module->course_id : $module->course_id) }}"
        class="btn btn-link mb-3">← Назад к курсу</a>

    <h3 class="mb-3">
        @if($isEdit)
            Редактирование урока: {{ $lesson->title }}
        @else
            Создать урок для модуля: {{ $module->title }}
        @endif
    </h3>

    <div class="card p-3">

        <form method="POST"
            action="{{ $isEdit ? route('teacher.lessons.update', $lesson->id) : route('teacher.lessons.store', $module->id) }}"
            enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Название урока</label>
                <input type="text" name="title" class="form-control"
                    value="{{ old('title', $isEdit ? $lesson->title : '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Учебный материал (текст)</label>
                <textarea name="content" rows="6"
                    class="form-control">{{ old('content', $isEdit ? $lesson->content : '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Файл задания (опционально)</label>
                <input type="file" name="assignment_file" class="form-control">
                @if($isEdit && $lesson->assignment_file)
                    <div class="mt-2">
                        Текущий файл:
                        <a href="{{ asset('storage/' . $lesson->assignment_file) }}" target="_blank">
                            {{ $lesson->assignment_file }}
                        </a>
                    </div>
                @endif
            </div>

            <hr>
            <h5>Условия открытия урока</h5>

            <div class="mb-3">
                <label class="form-label">Требуемый урок для открытия</label>
                <select name="required_lesson_id" class="form-select">
                    <option value="">Нет условий</option>

                    @if(isset($allLessons))
                        @foreach($allLessons as $l)
                            <option value="{{ $l->id }}" {{ old('required_lesson_id', $lesson->required_lesson_id ?? '') == $l->id ? 'selected' : '' }}>
                                {{ $l->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Минимальная оценка для открытия (если требуется)</label>
                <input type="number" name="required_min_score" class="form-control" min="1" max="100"
                    value="{{ old('required_min_score', $lesson->required_min_score ?? '') }}">
            </div>

            <p class="text-muted">
                Если указан требуемый урок, студент получит доступ только после сдачи/оценки этого урока.
            </p>

            <div class="mb-3">
                <label class="form-label">Доступен с</label>
                <input type="datetime-local" name="available_from" class="form-control"
                    value="{{ old('available_from', $isEdit && $lesson->available_from ? \Carbon\Carbon::parse($lesson->available_from)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Дедлайн</label>
                <input type="datetime-local" name="deadline" class="form-control"
                    value="{{ old('deadline', $isEdit && $lesson->deadline ? \Carbon\Carbon::parse($lesson->deadline)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Штраф за опоздание (%)</label>
                <input type="number" name="late_penalty_percent" class="form-control" min="0" max="100"
                    value="{{ old('late_penalty_percent', $isEdit ? $lesson->late_penalty_percent : 0) }}">
            </div>

            <button class="btn btn-primary">
                @if($isEdit) Сохранить изменения @else Создать урок @endif
            </button>
        </form>

    </div>

@endsection