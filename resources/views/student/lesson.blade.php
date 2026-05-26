@extends('layouts.app')

@section('title', $lesson->title)

@section('content')

    @php
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

        @php

            $glossaryTerms = \App\Models\Glossary::where(
                'course_id',
                $lesson->module->course->id
            )->get();

            function applyGlossary($text, $terms)
            {
                foreach ($terms as $term) {

                    $escaped = preg_quote($term->term, '/');

                    $replacement =
                        '<a href="' . route('student.glossary.show', $term->id) . '"
                            class="glossary-term"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="' . e($term->short_definition) . '">
                            $1
                        </a>';

                    $text = preg_replace(
                        '/\b(' . $escaped . ')\b/ui',
                        $replacement,
                        $text
                    );
                }

                return $text;
            }

        @endphp

        <div class="card p-3 mb-4">

            @if(empty($blocks))

                {!! applyGlossary($lesson->content, $glossaryTerms) !!}

            @else

                @foreach($blocks as $block)

                    @if($block['type'] === 'title')
                        <h2>{{ $block['value'] }}</h2>
                    @endif

                    @if($block['type'] === 'paragraph')
                        <div>
                            {!! applyGlossary($block['value'], $glossaryTerms) !!}
                        </div>
                    @endif

                    @if($block['type'] === 'image')
                        <img src="{{ $block['value'] }}" style="max-width:100%; margin-bottom:15px;">
                    @endif

                    @if($block['type'] === 'video')
                        <p>
                            <a href="{{ $block['value'] }}" target="_blank">
                                {{ $block['value'] }}
                            </a>
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

                <a href="{{ asset('storage/' . $lesson->assignment_file) }}" class="btn btn-primary mt-2">
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

    <style>
        .glossary-term {
            color: #2D75FB;
            font-weight: 600;
            border-bottom: 2px dotted #2D75FB;
            cursor: help;
            text-decoration: none;
            transition: 0.2s;
        }

        .glossary-term:hover {
            color: #1a56c9;
            border-bottom-color: #1a56c9;
        }
    </style>

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );

            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

        });

    </script>

@endsection