@extends('layouts.app')

@section('title', $lesson->title)

@section('content')

    @php
        // Проверяем, есть ли отправленная работа
        $submission = $lesson->submissions()
            ->where('student_id', auth()->id())
            ->first();
    @endphp

    @if(isset($accessAllowed) && !$accessAllowed)
        <div class="alert alert-warning">
            <h5>Материал заблокирован</h5>
            <p>Чтобы получить доступ, выполните условия:</p>

            <ul>
                @foreach($conditions as $cond)
                    <li>{{ $cond }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(!isset($accessAllowed) || $accessAllowed)
        <h3 class="mb-3">{{ $lesson->title }}</h3>


        @php
            $blocks = [];
            try {
                $blocks = json_decode($lesson->content, true) ?? [];
            } catch (\Exception $e) {
                $blocks = [];
            }
        @endphp

        <div class="card p-3 mb-4">

            @if(empty($blocks))
                {!! nl2br(e($lesson->content)) !!}
            @else

                @foreach($blocks as $block)

                    @if($block['type'] === 'title')
                        <h2>{{ $block['value'] }}</h2>
                    @endif

                    @if($block['type'] === 'paragraph')
                        <p>{!! nl2br(e($block['value'])) !!}</p>
                    @endif

                    @if($block['type'] === 'image')
                        <img src="{{ $block['value'] }}" style="max-width:100%; margin-bottom:15px;">
                    @endif

                    @if($block['type'] === 'video')
                        <p>
                            <a href="{{ $block['value'] }}" target="_blank">{{ $block['value'] }}</a>
                        </p>
                    @endif

                    @if($block['type'] === 'code')
                        <pre class="bg-light p-2 rounded"><code>{{ $block['value'] }}</code></pre>
                    @endif

                    @if($block['type'] === 'file')
                        <p>
                            <a href="{{ $block['value'] }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                📎 Скачать вложение
                            </a>
                        </p>
                    @endif

                @endforeach

            @endif
        </div>

        @if($lesson->assignment_file)
            <div class="alert alert-light border">
                <strong>Домашнее задание:</strong><br>
                <a href="{{asset('storage/' . $lesson->assignment_file) }}" class="btn btn-primary mt-2">
                    Скачать задание
                </a>
            </div>
        @endif

        @if($submission)
            <a href="{{ route('student.submissions.status', $lesson->id) }}" class="btn btn-success">
                Просмотреть отправленную работу
            </a>
        @else
            <a href="{{ route('student.submissions.create', $lesson->id) }}" class="btn btn-primary">
                Отправить работу
            </a>
        @endif
    @endif

@endsection