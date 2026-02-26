@extends('layouts.app')

@section('title', 'Вход')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-4">

        <div class="card p-3 shadow-sm">

            <h4 class="text-center mb-3">Вход</h4>

            @if($errors->any())
                <div class="alert alert-danger">Неверные данные для входа.</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Войти</button>
            </form>

        </div>

    </div>
</div>

@endsection
