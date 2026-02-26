@extends('layouts.app')

@section('title', 'Мои курсы')

@section('content')

    <h3 class="mb-3">Мои курсы</h3>

    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary mb-3">
        Создать курс
    </a>

    @if($courses->isEmpty())
        <div class="alert alert-info">У вас пока нет созданных курсов.</div>
    @else
        <div class="row">
            @foreach($courses as $course)
                <div class="col-md-4 mb-3">
                    <div class="card p-3 h-100">

                        <h5>{{ $course->title }}</h5>
                        <p class="text-muted">{{ $course->category->name ?? 'Без категории' }}</p>

                        <div class="mt-auto">

                            <a href="{{ route('teacher.courses.edit', $course->id) }}" class="btn btn-primary mb-2 w-100">
                                Открыть курс
                            </a>

                        </div>


                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection