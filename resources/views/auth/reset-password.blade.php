@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <h2>Новый пароль</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Новый пароль</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Подтверждение</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">
                Сменить пароль
            </button>
        </form>
    </div>
</div>
@endsection