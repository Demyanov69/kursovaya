@extends('layouts.app')

@section('title', $lesson->title)

@section('content')

    @php
        // Проверяем, есть ли отправленная работа
        $submission = $lesson->submissions()
            ->where('student_id', auth()->id())
            ->first();
    @endphp

    @if(isset($accessAllowed) && !$accessAllowed)
        <div class="alert alert-warning">
            <h5>Материал заблокирован</h5>
            <p>Чтобы получить доступ, выполните условия:</p>

            <ul>
                @foreach($conditions as $cond)
                    <li>{{ $cond }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(!isset($accessAllowed) || $accessAllowed)
        <h3 class="mb-3">{{ $lesson->title }}</h3>


        <div class="card p-3 mb-4">
            {!! nl2br(e($lesson->content)) !!}
        </div>

        @if($lesson->assignment_file)
            <div class="alert alert-light border">
                <strong>Домашнее задание:</strong><br>
                <a href="{{asset('storage/' . $lesson->assignment_file) }}" class="btn btn-primary mt-2">
                    Скачать задание
                </a>
            </div>
        @endif

        @if($submission)
            <a href="{{ route('student.submissions.status', $lesson->id) }}" class="btn btn-success">
                Просмотреть отправленную работу
            </a>
        @else
            <a href="{{ route('student.submissions.create', $lesson->id) }}" class="btn btn-primary">
                Отправить работу
            </a>
        @endif
    @endif

@endsection