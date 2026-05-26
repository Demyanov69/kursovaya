@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="mb-4">

            <a href="{{ route('teacher.portfolio.index') }}" class="btn btn-outline-secondary mb-3">

                ← Назад

            </a>

            <h2 class="fw-bold mb-1">
                {{ $course->title }}
            </h2>

            <div class="text-muted">
                Портфолио студентов курса
            </div>

        </div>

        @if($course->students->isEmpty())

            <div class="alert alert-info shadow-sm">
                На курсе пока нет студентов.
            </div>

        @else

            <div class="card border-0 shadow-sm">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Студент</th>
                                <th>Портфолио</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($course->students as $student)

                                <tr>

                                    <td>

                                        <div class="fw-semibold">
                                            {{ $student->name }}
                                        </div>

                                        <div class="text-muted small">
                                            {{ $student->email }}
                                        </div>

                                    </td>

                                    <td>

                                        @if($student->portfolio)

                                            <div class="fw-semibold">
                                                {{ $student->portfolio->title ?: 'Без названия' }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Нет портфолио
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($student->portfolio)

                                            @if($student->portfolio->visibility === 'public')

                                                <span class="badge bg-success">
                                                    Public
                                                </span>

                                            @elseif($student->portfolio->visibility === 'private')

                                                <span class="badge bg-warning text-dark">
                                                    Private
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    Draft
                                                </span>

                                            @endif

                                        @else

                                            <span class="badge bg-dark">
                                                Отсутствует
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        @if(
                                                $student->portfolio &&
                                                $student->portfolio->visibility !== 'draft'
                                            )

                                            <a href="{{ url('/portfolio/' . $student->portfolio->slug) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">

                                                Открыть

                                            </a>

                                        @else

                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                Недоступно
                                            </button>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>

@endsection