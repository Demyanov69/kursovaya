@extends('layouts.app')

@section('title', 'Кабинет преподавателя')

@section('content')

    <h3 class="mb-4">Добро пожаловать, {{ auth()->user()->name }}!</h3>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card p-3 h-100">
                <h5>Ваши курсы</h5>
                <p class="text-muted">Создавайте и редактируйте учебные материалы, отслеживайте отправленные работы.</p>
                <a href="{{ route('teacher.courses.index') }}" class="btn btn-primary">
                    Перейти к курсам
                </a>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-3 h-100">
                <h5>Проверка работ</h5>
                <p class="text-muted">Проверяйте отправленные домашние задания студентов и выставляйте оценки.</p>
                <a href="{{ route('teacher.submissions.all') }}" class="btn btn-success">
                    Проверить домашние задания
                </a>
            </div>
        </div>
    </div>

@endsection