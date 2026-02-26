@extends('layouts.app')

@section('title', 'Работы студентов')

@section('content')

<h3 class="mb-3">Отправленные работы</h3>

@if($submissions->isEmpty())
    <div class="alert alert-info">Пока никто не отправил работы.</div>
@else
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Урок</th>
            <th>Студент</th>
            <th>Статус</th>
            <th width="150">Действие</th>
        </tr>
    </thead>

    <tbody>
        @foreach($submissions as $s)
        <tr>
            <td>{{ $s->lesson->title }}</td>
            <td>{{ $s->student->name }}</td>
            <td>{{ $s->status }}</td>
            <td>
                <a href="{{ route('teacher.submissions.show', $s->id) }}"
                   class="btn btn-primary btn-sm">
                    Проверить
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
