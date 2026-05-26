@extends('layouts.app')

@section('title', 'Глоссарий курса')

@section('content')

<h3 class="mb-4">
    📘 Глоссарий курса: {{ $course->title }}
</h3>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Поиск термина..."
               value="{{ request('search') }}">

        <button class="btn btn-primary">
            Найти
        </button>
    </div>
</form>

@if($terms->isEmpty())
    <div class="alert alert-info">
        Термины пока отсутствуют.
    </div>
@else

    <div class="row">

        @foreach($terms as $term)

            <div class="col-md-6 mb-3">

                <div class="card h-100 shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="card-title">
                            {{ $term->term }}
                        </h5>

                        <p class="text-muted">
                            {{ $term->short_definition }}
                        </p>

                        <a href="{{ route('student.glossary.show', $term->id) }}"
                           class="btn btn-outline-primary btn-sm">
                            Подробнее
                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@endif

@endsection