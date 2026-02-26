@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')

    <h3 class="mb-3">Добро пожаловать, {{ auth()->user()->name }}!</h3>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card p-3 h-100">
                <h5>Ваши записанные курсы</h5>
                <p class="text-muted">Просматривайте материалы, выполняйте задания, отслеживайте прогресс.</p>

                <a href="{{ route('student.courses.index') }}" class="btn btn-primary">
                    Перейти к курсам
                </a>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-3 h-100">
                <h5>Ваши оценки</h5>
                <p class="text-muted">Посмотрите результаты работ, которые были проверены.</p>

                <a href="{{ route('student.grades') }}" class="btn btn-outline-primary">
                    Мои оценки
                </a>
            </div>
        </div>
    </div>

@endsection