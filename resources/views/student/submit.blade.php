@extends('layouts.app')

@section('title', 'Отправить работу')

@section('content')

<h3 class="mb-3">Отправка работы по уроку: {{ $lesson->title }}</h3>

<div class="card p-3">

    <form method="POST" action="{{ route('student.submissions.store', $lesson->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Текстовый ответ</label>
            <textarea class="form-control" name="text_answer" rows="5"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Файл ответа (опционально)</label>
            <input type="file" class="form-control" name="file_answer">
        </div>

        <button class="btn btn-primary">Отправить</button>

    </form>

</div>

@endsection
