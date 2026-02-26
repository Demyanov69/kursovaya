@extends('layouts.app')

@section('title', 'Проверка работы')

@section('content')

    <h3 class="mb-3">Проверка работы студента</h3>

    <div class="card p-3 mb-4">

        <p><strong>Урок:</strong> {{ $submission->lesson->title }}</p>
        <p><strong>Студент:</strong> {{ $submission->student->name }}</p>

        <p><strong>Ответ (текст):</strong></p>
        <div class="border p-2 mb-3">{{ $submission->text_answer ?? 'Нет текста' }}</div>

        @if($submission->file_answer)
            <p><strong>Файл ответа:</strong></p>
            <a href="{{ asset('storage/' . $submission->file_answer) }}">Скачать файл</a>
        @endif

    </div>

    <div class="card p-3">

        <form method="POST" action="{{ route('teacher.submissions.grade', $submission->id) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Оценка (1-100)</label>
                <input type="number" name="score" class="form-control" min="1" max="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Комментарий</label>
                <textarea name="comment" rows="4" class="form-control"></textarea>
            </div>

            <button class="btn btn-primary">Отправить оценку</button>
            <a href="{{ route('teacher.submissions.all') }}" class="btn btn-secondary">Назад к списку</a>
        </form>

    </div>

@endsection