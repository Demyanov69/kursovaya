@extends('layouts.app')

@section('title', 'Курсы')

@section('content')

<h3 class="mb-3">Доступные курсы</h3>

@if($courses->isEmpty())
    <div class="alert alert-info">Курсы пока отсутствуют.</div>
@else
    <div class="row">
        @foreach($courses as $course)
        <div class="col-md-4 mb-3">
            <div class="card p-3 h-100">

                <h5>{{ $course->title }}</h5>
                <p class="text-muted">{{ $course->category->name ?? 'Без категории' }}</p>

                <p>{{ Str::limit($course->description, 90) }}</p>

                <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary">
                    Открыть
                </a>

            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
