<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'LMS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #FFFFFF;
            color: #000000;
            font-size: 16px;
        }

        .navbar {
            background: #2D75FB;
        }

        .navbar a {
            color: #FFFFFF !important;
        }

        .btn-primary {
            background: #2D75FB;
            border-color: #2D75FB;
        }

        .btn-danger {
            background: #FA3585;
            border-color: #FA3585;
        }

        .card {
            border: 1px solid #e5e5e5;
        }

        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            padding: 20px 0;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">

            @auth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            @endauth

            <div class="collapse navbar-collapse" id="nav">
                @auth
                    <ul class="navbar-nav me-auto">

                        @if(auth()->user()->isStudent())
                            <li class="nav-item"><a class="nav-link" href="{{ route('student.dashboard') }}">Главная</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('student.courses.index') }}">Курсы</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('student.grades') }}">Оценки</a>
                            </li>
                        @endif

                        @if(auth()->user()->isTeacher())
                            <li class="nav-item"><a class="nav-link" href="{{ route('teacher.dashboard') }}">Главная</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('teacher.courses.index') }}">Курсы</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('teacher.submissions.all') }}">Проверить
                                    работы</a></li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.activity_logs') }}">Журнал действий</a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Админ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.courses.index') }}">Курсы</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Пользователи</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.activity_logs') }}">Журнал действий</a>
                            </li>
                        @endif

                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger">Выйти</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endauth
            </div>

        </div>
    </nav>

    <div class="container mb-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>