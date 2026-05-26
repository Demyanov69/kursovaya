@extends('layouts.app')

@section('content')

<div class="container">

    <div class="text-center mb-5">
        <h1>{{ $portfolio->title ?? 'Портфолио' }}</h1>
        <p class="text-muted">{{ $portfolio->description }}</p>
    </div>

    <div class="row">

        @foreach($portfolio->items as $item)

            <div class="col-md-4 mb-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body">

                        {{-- Тип --}}
                        <div class="mb-2 text-muted small">
                            {{ strtoupper($item->type) }}
                        </div>

                        <h5>{{ $item->title }}</h5>

                        @if($item->description)
                            <p>{{ $item->description }}</p>
                        @endif

                        @if($item->url)
                            <a href="{{ $item->url }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                🔗 Открыть
                            </a>
                        @endif

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection