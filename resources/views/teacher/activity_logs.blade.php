@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Журнал действий</h2>

    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Тип события</label>
            <select name="event_type" class="form-select">
                <option value="">Все</option>
                @foreach($eventTypes as $type)
                    <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Дата от</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Дата до</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Фильтр</button>
        </div>
    </form>

    <div class="mb-3">
        <a class="btn btn-success"
           href="{{ route('teacher.activity_logs.export', request()->query()) }}">
            Экспорт CSV
        </a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Тип</th>
                <th>IP</th>
                <th>Пользователь</th>
                <th>Курс</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->event_type }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->user?->name }}</td>
                    <td>{{ $log->course?->title ?? '-' }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}
</div>
@endsection