@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Портфолио студентов
            </h2>

            <div class="text-muted">
                Просмотр портфолио студентов ваших курсов
            </div>
        </div>

    </div>

    @if($courses->isEmpty())

        <div class="alert alert-info shadow-sm">
            У вас пока нет курсов.
        </div>

    @else

        <div class="row">

            @foreach($courses as $course)

                <div class="col-lg-4 col-md-6 mb-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body d-flex flex-column">

                            <div class="mb-3">

                                <div class="text-muted small mb-2">
                                    Курс
                                </div>

                                <h5 class="fw-bold">
                                    {{ $course->title }}
                                </h5>

                            </div>

                            <div class="mb-4">

                                <span class="badge bg-primary">
                                    Студентов: {{ $course->students_count }}
                                </span>

                            </div>

                            <div class="mt-auto">

                                <a href="{{ route('teacher.portfolio.course', $course->id) }}"
                                   class="btn btn-primary w-100">

                                    Открыть портфолио студентов

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection