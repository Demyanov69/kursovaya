@extends('layouts.app')

@section('title')
    @if(isset($lesson)) Редактировать урок @else Создать урок @endif
@endsection

@section('content')

    @php
        $isEdit = isset($lesson);
    @endphp

    <a href="{{ route('teacher.courses.edit', $isEdit ? $lesson->module->course_id : $module->course_id) }}"
        class="btn btn-link mb-3">← Назад к курсу</a>

    <h3 class="mb-3">
        @if($isEdit)
            Редактирование урока: {{ $lesson->title }}
        @else
            Создать урок для модуля: {{ $module->title }}
        @endif
    </h3>

    <div class="card p-3">

        <form method="POST"
            action="{{ $isEdit ? route('teacher.lessons.update', $lesson->id) : route('teacher.lessons.store', $module->id) }}"
            enctype="multipart/form-data">

            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            {{-- Вкладки --}}
            <ul class="nav nav-tabs mb-3" id="lessonTabs" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main" type="button"
                        role="tab">
                        Основное
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="builder-tab" data-bs-toggle="tab" data-bs-target="#builder" type="button"
                        role="tab">
                        Конструктор
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="assignment-tab" data-bs-toggle="tab" data-bs-target="#assignment"
                        type="button" role="tab">
                        Домашнее задание
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="conditions-tab" data-bs-toggle="tab" data-bs-target="#conditions"
                        type="button" role="tab">
                        Условия открытия
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="lessonTabsContent">

                {{-- ОСНОВНОЕ --}}
                <div class="tab-pane fade show active" id="main" role="tabpanel">

                    <div class="mb-3">
                        <label class="form-label">Название урока</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $isEdit ? $lesson->title : '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Доступен с</label>
                        <input type="datetime-local" name="available_from" class="form-control"
                            value="{{ old('available_from', $isEdit && $lesson->available_from ? \Carbon\Carbon::parse($lesson->available_from)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Дедлайн</label>
                        <input type="datetime-local" name="deadline" class="form-control"
                            value="{{ old('deadline', $isEdit && $lesson->deadline ? \Carbon\Carbon::parse($lesson->deadline)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Штраф за опоздание (%)</label>
                        <input type="number" name="late_penalty_percent" class="form-control" min="0" max="100"
                            value="{{ old('late_penalty_percent', $isEdit ? $lesson->late_penalty_percent : 0) }}">
                    </div>

                </div>

                {{-- КОНСТРУКТОР --}}
                <div class="tab-pane fade" id="builder" role="tabpanel">

                    <div class="mb-3">
                        <label class="form-label">Конструктор урока</label>

                        <div class="d-flex gap-2 flex-wrap mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('title')">Заголовок</button>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('paragraph')">Параграф</button>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('image')">Изображение</button>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('video')">Видео</button>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('code')">Код</button>

                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="addBlock('file')">Вложение</button>
                        </div>

                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <button type="button" class="btn btn-sm btn-success"
                                onclick="openPreview()">Предпросмотр</button>

                            <button type="button" class="btn btn-sm btn-warning" onclick="saveTemplate()">Сохранить как
                                шаблон</button>

                            <select id="templateSelect" class="form-select form-select-sm" style="width: 250px;">
                                <option value="">Импорт шаблона...</option>
                            </select>

                            <button type="button" class="btn btn-sm btn-secondary"
                                onclick="importTemplate()">Импортировать</button>
                        </div>

                        <div id="blocksContainer" class="border rounded p-3 bg-light">
                            <p class="text-muted">Добавьте блоки урока кнопками выше.</p>
                        </div>

                        <input type="hidden" name="blocks_json" id="blocksJson">
                    </div>

                </div>

                {{-- ДОМАШНЕЕ ЗАДАНИЕ --}}
                <div class="tab-pane fade" id="assignment" role="tabpanel">

                    <div class="mb-3">
                        <label class="form-label">Файл задания (опционально)</label>
                        <input type="file" name="assignment_file" class="form-control">

                        @if($isEdit && $lesson->assignment_file)
                            <div class="mt-2">
                                Текущий файл:
                                <a href="{{ asset('storage/' . $lesson->assignment_file) }}" target="_blank">
                                    {{ $lesson->assignment_file }}
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- УСЛОВИЯ --}}
                <div class="tab-pane fade" id="conditions" role="tabpanel">

                    <h5 class="mb-3">Условия открытия урока</h5>

                    <div class="mb-3">
                        <label class="form-label">Требуемый урок для открытия</label>
                        <select name="required_lesson_id" class="form-select">
                            <option value="">Нет условий</option>

                            @if(isset($allLessons))
                                @foreach($allLessons as $l)
                                    <option value="{{ $l->id }}" {{ old('required_lesson_id', $lesson->required_lesson_id ?? '') == $l->id ? 'selected' : '' }}>
                                        {{ $l->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Минимальная оценка для открытия</label>
                        <input type="number" name="required_min_score" class="form-control" min="1" max="100"
                            value="{{ old('required_min_score', $lesson->required_min_score ?? '') }}">
                    </div>

                    <p class="text-muted">
                        Если указан требуемый урок, студент получит доступ только после его выполнения.
                    </p>

                </div>

            </div>

            {{-- КНОПКА СОХРАНЕНИЯ --}}
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary px-4">
                    @if($isEdit) Сохранить изменения @else Создать урок @endif
                </button>
            </div>

        </form>

    </div>

    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        let blocks = [];

        @if($isEdit && $lesson->content)
            try {
                blocks = JSON.parse(@json($lesson->content));
            } catch (e) {
                blocks = [];
            }
        @endif

            function updateHiddenField() {
                document.getElementById("blocksJson").value = JSON.stringify(blocks);
            }

        function renderBlocks() {
            const container = document.getElementById("blocksContainer");
            container.innerHTML = "";

            if (blocks.length === 0) {
                container.innerHTML = `<p class="text-muted">Добавьте блоки урока кнопками выше.</p>`;
                updateHiddenField();
                return;
            }

            blocks.forEach((block, index) => {
                let html = "";

                if (block.type === "title") {
                    html = `<input type="text" class="form-control" placeholder="Заголовок"
                                    value="${block.value || ''}"
                                    oninput="blocks[${index}].value=this.value; updateHiddenField()">`;
                }

                if (block.type === "paragraph") {
                    html = `
                <div id="editor_${index}" style="background:white;"></div>
            `;
                }

                if (block.type === "image") {
                    html = `<input type="text" class="form-control" placeholder="URL изображения"
                                    value="${block.value || ''}"
                                    oninput="blocks[${index}].value=this.value; updateHiddenField()">`;
                }

                if (block.type === "video") {
                    html = `<input type="text" class="form-control" placeholder="URL видео (YouTube)"
                                    value="${block.value || ''}"
                                    oninput="blocks[${index}].value=this.value; updateHiddenField()">`;
                }

                if (block.type === "code") {
                    html = `<textarea class="form-control font-monospace" rows="4" placeholder="Код"
                                    oninput="blocks[${index}].value=this.value; updateHiddenField()">${block.value || ''}</textarea>`;
                }

                if (block.type === "file") {
                    html = `<input type="text" class="form-control" placeholder="Ссылка на файл"
                                    value="${block.value || ''}"
                                    oninput="blocks[${index}].value=this.value; updateHiddenField()">`;
                }

                container.innerHTML += `
                                <div class="card mb-2 block-item">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span style="cursor: grab;"><strong>${block.type.toUpperCase()}</strong></span>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeBlock(${index})">Удалить</button>
                                    </div>
                                    <div class="card-body">${html}</div>
                                </div>
                            `;
            });
            blocks.forEach((block, index) => {
                if (block.type === "paragraph") {

                    const editor = document.querySelector("#editor_" + index);

                    if (editor) {
                        const quill = new Quill(editor, {
                            theme: "snow",
                            modules: {
                                toolbar: [
                                    ["bold", "italic", "underline", "strike"],
                                    [{ "align": [] }],
                                    [{ "header": [1, 2, 3, false] }],
                                    [{ "list": "ordered" }, { "list": "bullet" }],
                                    ["link"],
                                    ["clean"]
                                ]
                            }
                        });

                        quill.root.innerHTML = block.value || "";

                        quill.on("text-change", function () {
                            blocks[index].value = quill.root.innerHTML;
                            updateHiddenField();
                        });
                    }
                }
            });
            updateHiddenField();
        }

        function addBlock(type) {
            blocks.push({ type: type, value: "" });
            renderBlocks();
        }

        function removeBlock(index) {
            blocks.splice(index, 1);
            renderBlocks();
        }

        function escapeHtml(text) {
            if (!text) return "";
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        function openPreview() {
            let previewHtml = "";

            blocks.forEach(block => {
                if (block.type === "title") previewHtml += `<h2>${escapeHtml(block.value)}</h2>`;
                if (block.type === "paragraph") previewHtml += `<div>${block.value}</div>`;
                if (block.type === "image") previewHtml += `<img src="${block.value}" style="max-width:100%; margin-bottom:15px;">`;
                if (block.type === "video") previewHtml += `<p><a href="${block.value}" target="_blank">${block.value}</a></p>`;
                if (block.type === "code") previewHtml += `<pre><code>${escapeHtml(block.value)}</code></pre>`;
                if (block.type === "file") previewHtml += `<p><a href="${block.value}" target="_blank">📎 Скачать файл</a></p>`;
            });

            const win = window.open("", "_blank");
            win.document.write(`<html><head><title>Предпросмотр</title></head><body style="font-family:Arial; padding:20px;">${previewHtml}</body></html>`);
        }

        new Sortable(document.getElementById("blocksContainer"), {
            animation: 150,
            handle: ".card-header",
            onEnd: function (evt) {
                const moved = blocks.splice(evt.oldIndex, 1)[0];
                blocks.splice(evt.newIndex, 0, moved);
                renderBlocks();
            }
        });

        async function loadTemplates() {
            const res = await fetch("{{ route('teacher.lesson_templates.index') }}");
            const data = await res.json();

            const select = document.getElementById("templateSelect");
            select.innerHTML = `<option value="">Импорт шаблона...</option>`;

            data.forEach(t => {
                select.innerHTML += `<option value="${t.id}">${t.name}</option>`;
            });
        }

        async function saveTemplate() {
            const name = prompt("Введите название шаблона:");
            if (!name) return;

            const res = await fetch("{{ route('teacher.lesson_templates.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    name: name,
                    blocks_json: JSON.stringify(blocks)
                })
            });

            const data = await res.json();
            if (data.success) {
                alert("Шаблон сохранён!");
                loadTemplates();
            }
        }

        async function importTemplate() {
            const templateId = document.getElementById("templateSelect").value;
            if (!templateId) return;

            const res = await fetch("{{ url('/teacher/lesson-templates') }}/" + templateId);
            const data = await res.json();

            blocks = JSON.parse(data.blocks_json);
            renderBlocks();
        }

        document.addEventListener("DOMContentLoaded", function () {
            renderBlocks();
            loadTemplates();
        });
    </script>

@endsection