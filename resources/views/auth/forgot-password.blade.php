@extends('layouts.app')

@section('title', 'Восстановление пароля')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-4">

        <div class="card p-3 shadow-sm">

            <h4 class="text-center mb-3">Восстановление пароля</h4>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" required>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary w-100">
                    Отправить ссылку
                </button>
            </form>

        </div>

    </div>
</div>

@endsection