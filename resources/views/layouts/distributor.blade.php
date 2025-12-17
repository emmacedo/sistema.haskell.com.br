<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel do Distribuidor') - Haskell Cosméticos</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        :root {
            --haskell-teal: #0d8784;
            --haskell-teal-dark: #0a6663;
            --haskell-teal-light: #10a19d;
            --haskell-pink: #e91e8c;
            --haskell-lime: #c3d933;
            --haskell-lime-dark: #a8bd2c;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--haskell-teal) 0%, var(--haskell-teal-dark) 100%);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .sidebar-header small {
            opacity: 0.8;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--haskell-lime);
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .sidebar-menu li a .badge {
            margin-left: auto;
            background: var(--haskell-pink);
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .sidebar-footer a:hover {
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-navbar h5 {
            margin: 0;
            color: #333;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info .avatar {
            width: 40px;
            height: 40px;
            background: var(--haskell-teal);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
        }

        .content-wrapper {
            padding: 25px;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card .icon.teal { background: rgba(13, 135, 132, 0.15); color: var(--haskell-teal); }
        .stat-card .icon.pink { background: rgba(233, 30, 140, 0.15); color: var(--haskell-pink); }
        .stat-card .icon.lime { background: rgba(195, 217, 51, 0.25); color: var(--haskell-lime-dark); }

        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Buttons */
        .btn-haskell {
            background: var(--haskell-teal);
            border-color: var(--haskell-teal);
            color: #fff;
        }

        .btn-haskell:hover {
            background: var(--haskell-teal-dark);
            border-color: var(--haskell-teal-dark);
            color: #fff;
        }

        .btn-outline-haskell {
            border-color: var(--haskell-teal);
            color: var(--haskell-teal);
        }

        .btn-outline-haskell:hover {
            background: var(--haskell-teal);
            color: #fff;
        }

        /* Tables */
        .table-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-card .card-header {
            background: var(--haskell-teal);
            color: #fff;
            padding: 15px 20px;
            border: none;
        }

        .table-card .card-body {
            padding: 20px;
        }

        /* Alerts */
        .alert-success {
            background-color: rgba(195, 217, 51, 0.2);
            border-color: var(--haskell-lime);
            color: #3d5a47;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>Haskell</h4>
            <small>Painel do Distribuidor</small>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('distributor.dashboard') }}" class="{{ request()->routeIs('distributor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('distributor.profile.edit') }}" class="{{ request()->routeIs('distributor.profile.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Dados da Empresa</span>
                </a>
            </li>
            <li>
                <a href="{{ route('distributor.cities') }}" class="{{ request()->routeIs('distributor.cities*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Cidades Atendidas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('distributor.sellers') }}" class="{{ request()->routeIs('distributor.sellers*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Vendedores</span>
                </a>
            </li>
            <li>
                <a href="{{ route('distributor.messages') }}" class="{{ request()->routeIs('distributor.messages*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Mensagens</span>
                    @if(isset($unreadMessages) && $unreadMessages > 0)
                        <span class="badge">{{ $unreadMessages }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('distributor.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-white p-0" style="text-decoration: none;">
                    <i class="bi bi-box-arrow-left"></i> Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link mobile-toggle me-2" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="user-info">
                <div class="avatar">
                    {{ substr($distributor->trade_name ?? 'D', 0, 1) }}
                </div>
                <div class="d-none d-md-block">
                    <strong>{{ $distributor->trade_name ?? 'Distribuidor' }}</strong>
                </div>
            </div>
        </nav>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery Mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>

    @yield('scripts')
</body>
</html>
