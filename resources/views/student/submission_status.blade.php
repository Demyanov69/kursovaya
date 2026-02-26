@extends('layouts.app')

@section('title', 'Статус работы')

@section('content')

<h3 class="mb-3">Статус отправленной работы</h3>

<div class="card p-3">

    <p><strong>Урок:</strong> {{ $submission->lesson->title }}</p>
    <p><strong>Статус:</strong> 
        @if($submission->status === 'in_progress')
            На проверке
        @elseif($submission->status === 'awaiting_review')
            Ожидает проверки
        @else
            Проверено
        @endif
    </p>

    @if($submission->grade)
        <p><strong>Оценка:</strong> {{ $submission->grade->score }}</p>
        <p><strong>Комментарий:</strong> {{ $submission->grade->comment }}</p>
    @endif

</div>

@endsection
