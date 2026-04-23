@extends('layouts.app')

@section('title', 'Отправить работу')

@section('content')

    <h3 class="mb-3">Отправка работы по уроку: {{ $lesson->title }}</h3>

    <div class="card p-3">

        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span id="draftStatus">Черновик: не сохранён</span>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-primary" id="saveDraftBtn">
                    Сохранить черновик
                </button>

                <button type="button" class="btn btn-sm btn-secondary" id="restoreDraftBtn" style="display:none;">
                    Восстановить черновик
                </button>
            </div>
        </div>
        <form method="POST" action="{{ route('student.submissions.store', $lesson->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Текстовый ответ</label>
                <textarea class="form-control" name="text_answer" rows="5"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Файл ответа (опционально)</label>
                <input type="file" class="form-control" name="file_answer">
            </div>

            <button class="btn btn-primary">Отправить</button>

        </form>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const textarea = document.querySelector("textarea[name='text_answer']");
            const status = document.getElementById("draftStatus");
            const restoreBtn = document.getElementById("restoreDraftBtn");

            const saveBtn = document.getElementById("saveDraftBtn");
            const lessonId = {{ $lesson->id }};
            const saveUrl = "{{ route('student.drafts.save', $lesson->id) }}";
            const loadUrl = "{{ route('student.drafts.load', $lesson->id) }}";

            async function checkDraft() {
                const response = await fetch(loadUrl);
                const data = await response.json();

                if (data.exists) {
                    restoreBtn.style.display = "inline-block";
                    status.innerText = "Найден черновик (обновлён: " + data.updated_at + ")";
                }
            }

            async function saveDraft() {
                if (!textarea) return;

                const text = textarea.value;

                const response = await fetch(saveUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        text_answer: text
                    })
                });

                const data = await response.json();

                if (data.success) {
                    status.innerText = "Черновик сохранён (" + data.saved_at + ")";
                }
            }

            restoreBtn.addEventListener("click", async function () {
                const response = await fetch(loadUrl);
                const data = await response.json();

                if (data.exists) {
                    textarea.value = data.text_answer ?? "";
                    status.innerText = "Черновик восстановлен (" + data.updated_at + ")";
                }
            });

            saveBtn.addEventListener("click", function () {
                saveDraft();
            });

            // автосохранение каждые 30 секунд
            setInterval(saveDraft, 30000);

            // проверка при загрузке страницы
            checkDraft();
        });
    </script>
@endsection