@extends('layouts.app')

@section('content')

    <div class="container">

        <h2 class="mb-4">Моё портфолио</h2>

        {{-- ✅ Уведомления --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ========================= --}}
        {{-- 🔧 НАСТРОЙКИ ПОРТФОЛИО --}}
        {{-- ========================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('student.portfolio.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" name="title" class="form-control" value="{{ $portfolio->title }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control">{{ $portfolio->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Доступ</label>
                        <select name="visibility" class="form-select">
                            <option value="draft" {{ $portfolio->visibility == 'draft' ? 'selected' : '' }}>Черновик</option>
                            <option value="private" {{ $portfolio->visibility == 'private' ? 'selected' : '' }}>По ссылке</option>
                            <option value="public" {{ $portfolio->visibility == 'public' ? 'selected' : '' }}>Публичный</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">Сохранить</button>
                </form>

                @if($portfolio->slug && $portfolio->visibility !== 'draft')

                    <div class="mt-3">
                        <label class="form-label">Ссылка на портфолио</label>

                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ url('/portfolio/' . $portfolio->slug) }}" readonly>

                            <a href="{{ url('/portfolio/' . $portfolio->slug) }}" target="_blank" class="btn btn-outline-primary">
                                Открыть
                            </a>
                        </div>
                    </div>

                @endif

                <div class="mt-3">
                    <a href="{{ route('student.portfolio.pdf') }}" class="btn btn-danger">
                        📄 Скачать PDF
                    </a>
                </div>

            </div>
        </div>

        {{-- ========================= --}}
        {{-- ➕ ДОБАВЛЕНИЕ ЭЛЕМЕНТА --}}
        {{-- ========================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">Добавить элемент</h5>

                <form method="POST" action="{{ route('student.portfolio.items.store') }}">
                    @csrf

                    <div class="row g-2">

                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="project">Проект</option>
                                <option value="link">Ссылка</option>
                                <option value="text">Текст</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="title" class="form-control" placeholder="Название">
                        </div>

                        <div class="col-md-3">
                            <input type="url" name="link" class="form-control" placeholder="URL">
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-success w-100">Добавить</button>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        {{-- ========================= --}}
        {{-- 📦 СПИСОК ЭЛЕМЕНТОВ --}}
        {{-- ========================= --}}
        <div class="row">

            @forelse($portfolio->items as $item)

                <div class="col-md-4 mb-3">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body">

                            {{-- Тип --}}
                            <div class="text-muted small mb-2">
                                {{ strtoupper($item->type) }}
                            </div>

                            <h5 class="mb-2">{{ $item->title }}</h5>

                            @if($item->description)
                                <p class="text-muted">{{ $item->description }}</p>
                            @endif

                            {{-- ссылка --}}
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    Открыть
                                </a>
                            @endif

                        </div>

                        {{-- действия --}}
                        <div class="card-footer d-flex justify-content-between">

                            {{-- редактировать --}}
                            <a href="{{ route('student.portfolio.items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                ✏️
                            </a>

                            {{-- удалить --}}
                            @if($item->type !== 'course')
                                <form method="POST" action="{{ route('student.portfolio.items.delete', $item->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>
                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Пока нет элементов. Добавь первый 👆
                    </div>
                </div>

            @endforelse

        </div>

    </div>

@endsection