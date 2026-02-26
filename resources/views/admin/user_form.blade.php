@extends('layouts.app')

@section('title', isset($user) ? 'Редактирование пользователя' : 'Создание пользователя')

@section('content')

<h3 class="mb-3">
    {{ isset($user) ? 'Редактирование пользователя: ' . $user->name : 'Создание пользователя' }}
</h3>

<div class="card p-3">

    <form method="POST" 
          action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Имя *</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $user->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" 
                   value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        @if(!isset($user))
        <div class="mb-3">
            <label class="form-label">Пароль *</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        @else
        <div class="mb-3">
            <label class="form-label">Новый пароль (оставьте пустым, если не меняется)</label>
            <input type="password" name="password" class="form-control">
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Роль *</label>
            <select name="role_id" class="form-select" required>
                <option value="">Выберите роль</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Факультет</label>
            <input type="text" name="faculty" class="form-control" 
                   value="{{ old('faculty', $user->faculty ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Направление</label>
            <input type="text" name="direction" class="form-control" 
                   value="{{ old('direction', $user->direction ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Год обучения</label>
            <input type="number" name="course_year" class="form-control" min="1" max="5"
                   value="{{ old('course_year', $user->course_year ?? '') }}">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                {{ isset($user) ? 'Обновить' : 'Создать' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
        </div>
    </form>

</div>

@endsection