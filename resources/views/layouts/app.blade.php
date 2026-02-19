<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kanban App</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5;
        }

        .navbar {
            background-color: #0d6efd;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('boards.index') }}">
                <i class="bi bi-kanban"></i> Kanban App
            </a>
            <div class="ms-auto">
                <span class="text-white me-3">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SortableJS (drag and drop) -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @yield('scripts')
</body>

</html>