<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 35px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #2d3748;
            font-size: 13px;
            line-height: 1.6;
        }

        /* HEADER */

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }

        .author {
            font-size: 16px;
            color: #6b7280;
        }

        .description-box {
            background: #f8fafc;
            border-left: 5px solid #2563eb;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 6px;
        }

        /* SECTION */

        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111827;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 8px;
        }

        /* ITEM */

        .item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 18px;
            background: #ffffff;

            page-break-inside: avoid;
        }

        .item-header {
            margin-bottom: 10px;
        }

        .item-type {
            display: inline-block;
            font-size: 11px;
            font-weight: bold;
            color: white;
            background: #2563eb;
            padding: 4px 10px;
            border-radius: 30px;
            margin-bottom: 10px;
        }

        .item-title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .item-description {
            margin-top: 12px;
            color: #374151;
        }

        .item-link-box {
            margin-top: 15px;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 6px;
        }

        .item-link {
            color: #2563eb;
            text-decoration: none;
            word-break: break-all;
            font-size: 12px;
        }

        /* FOOTER */

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #9ca3af;
            font-size: 11px;
        }

        /* BADGES */

        .project {
            background: #2563eb;
        }

        .link {
            background: #059669;
        }

        .text {
            background: #7c3aed;
        }

        .course {
            background: #dc2626;
        }

        /* PAGE BREAK FIX */

        table {
            page-break-inside: avoid;
        }

        div {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">

        <div class="title">
            {{ $portfolio->title }}
        </div>

        <div class="author">
            Автор: {{ $portfolio->user->name ?? 'Пользователь' }}
        </div>

    </div>

    {{-- DESCRIPTION --}}
    @if($portfolio->description)
        <div class="description-box">
            {{ $portfolio->description }}
        </div>
    @endif

    {{-- ITEMS --}}
    <div class="section-title">
        Проекты и достижения
    </div>

    @foreach($portfolio->items as $item)

        <div class="item">

            <div class="item-header">

                <div class="item-type {{ $item->type }}">
                    {{ strtoupper($item->type) }}
                </div>

                <div class="item-title">
                    {{ $item->title }}
                </div>

            </div>

            @if($item->description)
                <div class="item-description">
                    {{ $item->description }}
                </div>
            @endif

            @if($item->url)
                <div class="item-link-box">

                    <strong>Ссылка:</strong>

                    <div class="item-link">
                        {{ $item->url }}
                    </div>

                </div>
            @endif

        </div>

    @endforeach

    {{-- FOOTER --}}
    <div class="footer">
        Портфолио сгенерировано {{ now()->format('d.m.Y H:i') }}
    </div>

</body>

</html>