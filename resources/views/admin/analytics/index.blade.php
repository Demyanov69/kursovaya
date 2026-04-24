@extends('layouts.app')

@section('title', 'Админ аналитика')

@section('content')

<h3>Аналитика активности пользователей</h3>

<hr>

<h5>Общая активность по дням</h5>
<canvas id="chartAll" height="80"></canvas>

<hr>

<h5>Пользователи</h5>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Карточка</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role->name ?? '' }}</td>
                <td>
                    <a href="{{ route('admin.analytics.user', $u->id) }}" class="btn btn-sm btn-primary">
                        Открыть
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById("chartAll"), {
    type: "line",
    data: {
        labels: @json($all->pluck('day')),
        datasets: [{
            label: "Активность всех пользователей",
            data: @json($all->pluck('total')),
            borderWidth: 2,
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>

@endsection