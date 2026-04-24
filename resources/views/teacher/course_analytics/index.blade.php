@extends('layouts.app')

@section('title', 'Аналитика курса')

@section('content')
<h3>Аналитика курса: {{ $course->title }}</h3>

<hr>

<h5>Общая активность</h5>
<canvas id="chartAll" height="80"></canvas>

<h5 class="mt-4">Входы</h5>
<canvas id="chartLogins" height="80"></canvas>

<h5 class="mt-4">Открытие уроков</h5>
<canvas id="chartOpens" height="80"></canvas>

<h5 class="mt-4">Отправка заданий</h5>
<canvas id="chartSubs" height="80"></canvas>

<hr>

<h5>Студенты курса</h5>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Студент</th>
            <th>Email</th>
            <th>Карточка</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $st)
            <tr>
                <td>{{ $st->name }}</td>
                <td>{{ $st->email }}</td>
                <td>
                    <a class="btn btn-sm btn-primary"
                       href="{{ route('teacher.course.analytics.student', [$course->id, $st->id]) }}">
                        Открыть
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

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