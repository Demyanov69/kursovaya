@extends('layouts.app')

@section('title', 'Управление глоссарием')

@section('content')

    <h3 class="mb-3">📘 Глоссарий курса: {{ $course->title }}</h3>

    <div class="mb-3">
        <a href="{{ route('teacher.glossary.create', $course->id) }}" class="btn btn-primary">
            + Добавить термин
        </a>
    </div>

    @if($terms->isEmpty())
        <div class="alert alert-info">
            Термины отсутствуют.
        </div>
    @else
        @foreach($terms as $term)
            <div class="card p-3 mb-3">

                <div class="d-flex justify-content-between align-items-start">

                    <div class="flex-grow-1">

                        <h5>{{ $term->term }}</h5>

                        <p class="text-muted mb-2">
                            {{ $term->short_definition }}
                        </p>

                        <div class="mb-2">

                            <span class="badge bg-warning text-dark">
                                ⭐ Рейтинг:
                                {{ number_format($term->averageRating(), 1) }}
                            </span>

                            <span class="badge bg-secondary">
                                💬 Комментариев:
                                {{ $term->comments->count() }}
                            </span>

                        </div>

                        @if($term->comments->count())

                            <div class="mt-3">

                                <strong>Последние комментарии:</strong>

                                @foreach($term->comments->take(3) as $comment)

                                    <div class="border rounded p-2 mt-2 bg-light">

                                        <strong>
                                            {{ $comment->user->name }}
                                        </strong>

                                        <div class="small text-muted">
                                            {{ $comment->created_at->format('d.m.Y H:i') }}
                                        </div>

                                        <div class="mt-1">
                                            {{ $comment->comment }}
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                    <div class="d-flex gap-2 ms-3">

                        <a href="{{ route('teacher.glossary.edit', $term->id) }}" class="btn btn-sm btn-outline-primary">
                            Редактировать
                        </a>

                        <form method="POST" action="{{ route('teacher.glossary.destroy', $term->id) }}">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить термин?')">

                                Удалить

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        @endforeach
    @endif

@endsection