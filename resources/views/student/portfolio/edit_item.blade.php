@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-4">Редактировать элемент</h2>

    <form method="POST" action="{{ route('student.portfolio.items.update', $item->id) }}">
        @csrf
        @method('PUT')

        {{-- Тип --}}
        <div class="mb-3">
            <label class="form-label">Тип элемента</label>
            <select name="type" class="form-select">
                <option value="project" {{ $item->type=='project'?'selected':'' }}>Проект</option>
                <option value="link" {{ $item->type=='link'?'selected':'' }}>Ссылка</option>
                <option value="text" {{ $item->type=='text'?'selected':'' }}>Текст</option>
            </select>
            <small class="text-muted">
                Выберите тип: проект (работа), ссылка (внешний ресурс), текст (описание)
            </small>
        </div>

        {{-- Название --}}
        <div class="mb-3">
            <label class="form-label">Название</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title', $item->title) }}"
                   placeholder="Например: Интернет-магазин на Laravel">
            <small class="text-muted">
                Короткое название проекта или ссылки
            </small>
        </div>

        {{-- Описание --}}
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description"
                      class="form-control"
                      rows="3"
                      placeholder="Кратко опишите, что это за проект или материал">{{ old('description', $item->description) }}</textarea>
            <small class="text-muted">
                Опционально: что это, какие технологии использованы и т.д.
            </small>
        </div>

        {{-- URL --}}
        <div class="mb-3">
            <label class="form-label">Ссылка (URL)</label>
            <input type="text"
                   name="url"
                   class="form-control"
                   value="{{ old('url', $item->url) }}"
                   placeholder="https://github.com/username/project">
            <small class="text-muted">
                Укажите ссылку на проект, GitHub, сайт или внешний ресурс
            </small>
        </div>

        <button class="btn btn-primary">Сохранить</button>

    </form>

</div>

@endsection