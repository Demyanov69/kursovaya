@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Календарь</h2>

        <select id="eventFilter" class="form-select" style="max-width: 220px;">
            <option value="all">Все</option>
            <option value="deadline">Дедлайны</option>
            <option value="available">Открытия</option>
        </select>
    </div>

    <div id="calendar"></div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
#calendar {
    max-width: 1100px;
    margin: auto;
}

/* компактность */
.fc-timegrid {
    max-height: 650px;
    overflow-y: auto;
}

/* события */
.fc-event {
    border-radius: 6px;
    font-size: 13px;
    text-align: center;
}

/* цвета */
.event-available { background: #2ecc71 !important; }
.event-deadline { background: #3498db !important; }
.event-overdue {
    background: #e74c3c !important;
    border: 2px solid #000 !important;
}
.event-past { background: #bdc3c7 !important; }

/* today кнопка */
.fc-today-button {
    background: #2c3e50 !important;
    color: #fff !important;
    border-radius: 8px !important;
    padding: 6px 14px !important;
}

/* hover */
.fc-button:hover {
    opacity: 0.85;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        height: 'auto',

        editable: true, // 🔥 drag&drop

        events: function(fetchInfo, successCallback) {
            const filter = document.getElementById('eventFilter').value;

            fetch(`/calendar/events?filter=${filter}`)
                .then(res => res.json())
                .then(data => successCallback(data));
        },

        // 🔥 Tooltip
        eventDidMount: function(info) {
            let tooltip = info.event.extendedProps.full_title +
                "\nКурс: " + info.event.extendedProps.course;

            info.el.setAttribute("title", tooltip);
        },

        // 🔥 Клик по событию
        eventClick: function(info) {

            const lessonId = info.event.extendedProps.lesson_id;
            const role = "{{ auth()->user()->role->name }}";

            if (!lessonId) return;

            if (role === 'teacher') {
                window.location.href = `/teacher/lessons/${lessonId}/edit`;
            } else if (role === 'student') {
                window.location.href = `/student/lessons/${lessonId}`;
            } else {
                window.location.href = `/admin/courses`;
            }
        },

        // 🔥 Drag & Drop
        eventDrop: function(info) {

            fetch('/calendar/update-date', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    lesson_id: info.event.extendedProps.lesson_id,
                    date: info.event.startStr,
                    type: info.event.extendedProps.type
                })
            });
        },

        // 🔥 Создание урока (клик по дате)
        dateClick: function(info) {

            const role = "{{ auth()->user()->role->name }}";

            if (role === 'teacher') {
                window.location.href =
                    `/teacher/courses?create_lesson_date=${info.dateStr}`;
            }
        },

        slotMinTime: "08:00:00",
        slotMaxTime: "22:00:00"

    });

    calendar.render();

    // 🔥 фильтр
    document.getElementById('eventFilter').addEventListener('change', function () {
        calendar.refetchEvents();
    });

});
</script>

@endsection