@extends('layouts.app')

@section('title', $glossary->term)

@section('content')
    <a href="{{ url()->previous() }}" class="btn btn-link mb-3">
        ← Назад
    </a>

    <div class="card p-4">
        <h2 class="mb-3">{{ $glossary->term }}</h2>

        <div class="alert alert-light border">
            <strong>Краткое определение:</strong><br>
            {{ $glossary->short_definition }}
        </div>

        <div class="mb-4">
            {!! nl2br(e($glossary->full_definition)) !!}
            <hr>

            <div class="mb-4">

                <h5>Средняя оценка</h5>

                <div class="mb-2">
                    ⭐ {{ number_format($glossary->averageRating(), 1) }}/5
                </div>

                <form method="POST" action="{{ route('student.glossary.rate', $glossary->id) }}" class="d-flex gap-2">

                    @csrf

                    <select name="rating" class="form-select w-auto">

                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor

                    </select>

                    <button class="btn btn-primary btn-sm">
                        Оценить
                    </button>

                </form>

            </div>

            <hr>

            <div class="mb-4">

                <h5>Комментарии</h5>

                <form method="POST" action="{{ route('student.glossary.comment', $glossary->id) }}">

                    @csrf

                    <textarea name="comment" rows="3" class="form-control mb-2"
                        placeholder="Введите комментарий..."></textarea>

                    <button class="btn btn-primary btn-sm">
                        Отправить
                    </button>

                </form>

            </div>

            @if($glossary->comments->isEmpty())

                <p class="text-muted">
                    Комментариев пока нет.
                </p>

            @else

                @foreach($glossary->comments as $comment)

                    <div class="card p-3 mb-2">

                        <strong>
                            {{ $comment->user->name }}
                        </strong>

                        <div class="text-muted small mb-2">
                            {{ $comment->created_at->format('d.m.Y H:i') }}
                        </div>

                        <div>
                            {{ $comment->comment }}
                        </div>

                    </div>

                @endforeach

            @endif
        </div>

        <hr>

        <small class="text-muted">
            Автор: {{ $glossary->author->name }}
        </small>
    </div>
@endsection