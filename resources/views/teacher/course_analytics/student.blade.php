@extends('layouts.app')

@section('title', 'Карточка активности')

@section('content')

<h3>Активность студента</h3>

<div class="card p-3 mb-3">
    <strong>Курс:</strong> {{ $course->title }} <br>
    <strong>Студент:</strong> {{ $student->name }} <br>
    <strong>Email:</strong> {{ $student->email }}
</div>

<h5>Общая активность</h5>
<canvas id="chartAll" height="80"></canvas>

<h5 class="mt-4">Входы</h5>
<canvas id="chartLogins" height="80"></canvas>

<h5 class="mt-4">Открытие уроков</h5>
<canvas id="chartOpens" height="80"></canvas>

<h5 class="mt-4">Отправка заданий</h5>
<canvas id="chartSubs" height="80"></canvas>

<hr>

<h5>Heatmap активности (по часам)</h5>

<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Дата \ Час</th>
            @for($h=0; $h<24; $h++)
                <th>{{ $h }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @php
            $heatData = [];
            foreach($heatmap as $row) {
                $heatData[$row->day][$row->hour] = $row->total;
            }
        @endphp

        @foreach($heatData as $day => $hours)
            <tr>
                <td><strong>{{ $day }}</strong></td>
                @for($h=0; $h<24; $h++)
                    @php
                        $val = $hours[$h] ?? 0;
                        $color = $val == 0 ? '#ffffff' : ($val < 3 ? '#d1e7dd' : ($val < 6 ? '#0f5132' : '#842029'));
                    @endphp
                    <td style="background: {{ $color }};">
                        {{ $val > 0 ? $val : '' }}
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function makeChart(canvasId, labels, data, labelName, type="bar") {
    new Chart(document.getElementById(canvasId), {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: labelName,
                data: data,
                borderWidth: 1
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