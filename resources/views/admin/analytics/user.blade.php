@extends('layouts.app')

@section('title', 'Активность пользователя')

@section('content')

<h3>Карточка активности пользователя</h3>

<div class="card p-3 mb-3">
    <strong>Имя:</strong> {{ $user->name }} <br>
    <strong>Email:</strong> {{ $user->email }} <br>
    <strong>Роль:</strong> {{ $user->role->name ?? '' }}
</div>

<h5>Общая активность</h5>
<canvas id="chartAll" height="80"></canvas>

<h5 class="mt-4">Входы</h5>
<canvas id="chartLogins" height="80"></canvas>

<h5 class="mt-4">Открытие уроков</h5>
<canvas id="chartOpens" height="80"></canvas>

<h5 class="mt-4">Отправка заданий</h5>
<canvas id="chartSubs" height="80"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function makeChart(canvasId, labels, data, labelName, type="line") {
    new Chart(document.getElementById(canvasId), {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: labelName,
                data: data,
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}

makeChart("chartAll", @json($all->pluck('day')), @json($all->pluck('total')), "Активность");
makeChart("chartLogins", @json($logins->pluck('day')), @json($logins->pluck('total')), "Входы");
makeChart("chartOpens", @json($opens->pluck('day')), @json($opens->pluck('total')), "Открытие уроков");
makeChart("chartSubs", @json($subs->pluck('day')), @json($subs->pluck('total')), "Отправка заданий");
</script>

@endsection