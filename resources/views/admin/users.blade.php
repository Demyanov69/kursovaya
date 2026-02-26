@extends('layouts.app')

@section('title', isset($users) ? 'Пользователи' : 'Создание пользователя')

@section('content')

@if(isset($users))
    {{-- Режим просмотра списка пользователей --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Управление пользователями</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            Создать пользователя
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <div class="alert alert-info">Пользователи не найдены.</div>
    @else
        <div class="card p-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Факультет</th>
                        <th width="200">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge 
                                @if($user->role->name === 'admin') bg-danger
                                @elseif($user->role->name === 'teacher') bg-primary
                                @else bg-success @endif">
                                {{ $user->role->name }}
                            </span>
                        </td>
                        <td>{{ $user->faculty ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-primary btn-sm">Редактировать</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Удалить пользователя?')">
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
    {{-- Режим создания пользователя --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Создание пользователя</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            ← Назад к списку
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-3">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Роль</label>
                <select name="role_id" class="form-select" required>
                    <option value="">Выберите роль</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Факультет (необязательно)</label>
                <input type="text" name="faculty" class="form-control" value="{{ old('faculty') }}">
            </div>

            <button type="submit" class="btn btn-primary">Создать пользователя</button>
        </form>
    </div>
@endif

@endsection