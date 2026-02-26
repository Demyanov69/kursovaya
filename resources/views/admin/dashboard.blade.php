@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')

<h3 class="mb-4">Административная панель</h3>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $totalUsers }}</h4>
            <p class="mb-0">Всего пользователей</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $totalStudents }}</h4>
            <p class="mb-0">Студентов</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $totalTeachers }}</h4>
            <p class="mb-0">Преподавателей</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $totalCourses }}</h4>
            <p class="mb-0">Курсов</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $totalEnrollments }}</h4>
            <p class="mb-0">Записей на курсы</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card p-3 text-center">
            <h4>{{ $activity }}</h4>
            <p class="mb-0">Отправленных работ</p>
        </div>
    </div>
</div>

<div class="card p-3 mt-3">
    <h5>Быстрые действия</h5>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Управление пользователями</a>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-primary">Управление курсами</a>
    </div>
</div>

@endsection