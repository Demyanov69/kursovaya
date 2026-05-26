@extends('layouts.app')

@section('title', 'Смена пароля')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-4">

        <div class="card p-3 shadow-sm">

            <h4 class="text-center mb-3">Смена пароля</h4>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Текущий пароль</label>
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" name="new_password" class="form-control" required>
                    @error('new_password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Подтверждение</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">
                    Сменить пароль
                </button>
            </form>

        </div>

    </div>
</div>

@endsection