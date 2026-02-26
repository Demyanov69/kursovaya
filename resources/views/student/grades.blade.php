@extends('layouts.app')

@section('title', 'Мои оценки')

@section('content')

<h3 class="mb-3">Мои оценки</h3>

@if($grades->isEmpty())
    <div class="alert alert-info">
        У вас пока нет оценённых работ.
    </div>
@else

    <div class="list-group">

        @foreach($grades as $grade)
            <div class="list-group-item">

                <h5 class="mb-1">
                    {{ $grade->submission->lesson->title }}
                </h5>

                <p class="mb-1">
                    <strong>Оценка:</strong> {{ $grade->score }}
                </p>

                @if($grade->comment)
                    <p class="text-muted">{{ $grade->comment }}</p>
                @endif

                <a href="{{ route('student.submissions.status', $grade->submission->lesson_id) }}"
                   class="btn btn-sm btn-outline-primary mt-2">
                    Посмотреть работу
                </a>

            </div>
        @endforeach

    </div>

@endif

@endsection
