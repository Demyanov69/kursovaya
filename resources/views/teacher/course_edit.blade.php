@extends('layouts.app')

@section('title', isset($course) ? 'Управление курсом' : 'Создание курса')

@section('content')

    <h3 class="mb-3">
        @isset($course)
            Управление курсом: {{ $course->title }}
        @else
            Создание нового курса
        @endisset
    </h3>

    @isset($course)
        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('teacher.course.analytics', $course->id) }}" class="btn btn-primary">
                Аналитика
            </a>
        </div>
    @endisset

    {{-- Блок основной информации о курсе --}}
    <div class="card p-3 mb-4">
        <h5 class="mb-3">Основная информация</h5>

        <form method="POST"
            action="{{ isset($course) ? route('teacher.courses.update', $course->id) : route('teacher.courses.store') }}">
            @csrf
            @isset($course)
                @method('PUT')
            @endisset

            <div class="mb-3">
                <label class="form-label">Название курса</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $course->title ?? '') }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control"
                    rows="4">{{ old('description', $course->description ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Категория</label>
                <select name="category_id" class="form-select">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((isset($course) && $course->category_id == $cat->id) || old('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">
                @isset($course)
                    Сохранить изменения
                @else
                    Создать курс
                @endisset
            </button>
        </form>
    </div>

    {{-- Блок управления студентами (только для редактирования существующего курса) --}}
    @isset($course)
        <div class="card p-3 mb-4">
            <h5 class="mb-3">Управление студентами</h5>

            <div class="row">
                <div class="col-md-6">
                    <h6>Студенты на курсе ({{ $course->students->count() }})</h6>
                    @if($course->students->isEmpty())
                        <p class="text-muted">На курс еще не добавлены студенты.</p>
                    @else
                        <div class="list-group">
                            @foreach($course->students as $student)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $student->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $student->email }}</small>
                                    </div>
                                    <form method="POST"
                                        action="{{ route('teacher.courses.removeStudent', [$course->id, $student->id]) }}"
                                        onsubmit="return confirm('Удалить студента с курса?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <h6>Добавить студентов</h6>
                    <form method="POST" action="{{ route('teacher.courses.addStudents', $course->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Выберите студентов</label>
                            <select name="student_ids[]" class="form-select" multiple size="5">
                                @foreach($allStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Для выбора нескольких студентов удерживайте Ctrl</small>
                        </div>
                        <button type="submit" class="btn btn-success">Добавить студентов</button>
                    </form>
                </div>
            </div>
        </div>
    @endisset

    {{-- Блок модулей и уроков (только для редактирования существующего курса) --}}
    @isset($course)
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0">Модули курса</h5>
                <a href="{{ route('teacher.modules.create', $course->id) }}" class="btn btn-primary">
                    + Добавить модуль
                </a>
            </div>

            @if($course->modules->isEmpty())
                <div class="alert alert-info">
                    Модули еще не созданы.
                </div>
            @else
                @foreach($course->modules as $module)
                    <div class="border rounded p-3 mb-3">

                        {{-- Заголовок + кнопки действий над модулем --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="m-0">{{ $module->position }}. {{ $module->title }}</h5>

                            <div class="d-flex gap-2">
                                {{-- Редактировать модуль --}}
                                <a href="{{ route('teacher.modules.edit', $module->id) }}" class="btn btn-sm btn-outline-primary">
                                    Редактировать
                                </a>

                                {{-- Удалить модуль --}}
                                <form method="POST" action="{{ route('teacher.modules.delete', $module->id) }}"
                                    onsubmit="return confirm('Удалить модуль? Все уроки внутри будут удалены!')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Кнопка добавления урока --}}
                        <a href="{{ route('teacher.lessons.create', $module->id) }}" class="btn btn-outline-primary btn-sm mb-3">
                            + Добавить урок
                        </a>

                        {{-- Список уроков --}}
                        @if($module->lessons->isEmpty())
                            <p class="text-muted">Уроки отсутствуют.</p>
                        @else
                            <ul class="list-group">
                                @foreach($module->lessons as $lesson)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <strong>{{ $lesson->title }}</strong><br>
                                            <small class="text-muted">
                                                Дедлайн: {{ $lesson->deadline ?? 'не указан' }}
                                            </small>
                                        </div>

                                        <div class="d-flex gap-2 align-items-center">
                                            {{-- Кнопка проверки работ --}}
                                            <a href="{{ route('teacher.submissions.index', $lesson->id) }}"
                                                class="btn btn-sm btn-outline-success">
                                                Проверить работы
                                            </a>

                                            {{-- Редактировать урок --}}
                                            <a href="{{ route('teacher.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-outline-primary">
                                                Редактировать
                                            </a>

                                            {{-- Удалить урок --}}
                                            <form method="POST" action="{{ route('teacher.lessons.destroy', $lesson->id) }}"
                                                onsubmit="return confirm('Удалить урок? Все отправки студентов будут удалены!')"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </div>
                @endforeach
            @endif
        </div>
    @endisset

@endsection