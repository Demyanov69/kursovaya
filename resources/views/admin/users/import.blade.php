@extends('layouts.app')

@section('title', 'Импорт пользователей')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="card p-3 shadow-sm">

            <h4 class="text-center mb-3">Импорт пользователей (CSV)</h4>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.import.post') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">CSV файл</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100 mb-2">
                    Загрузить
                </button>
            </form>

            <a href="{{ route('admin.users.export') }}" class="btn btn-outline-secondary w-100">
                Скачать пользователей (CSV)
            </a>

        </div>

    </div>
</div>

@endsection