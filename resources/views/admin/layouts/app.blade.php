<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwasthyaSearch Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
            color: #1e293b; /* slate-800 */
            overflow-x: hidden;
        }
        /* Sidebar Styling */
        .admin-sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #0F172B; /* Premium Dark Navy */
            color: #cbd5e1; /* slate-300 */
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        .admin-sidebar .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .admin-sidebar .sidebar-brand span {
            color: #14b8a6; /* Teal accent */
        }
        .admin-sidebar .nav-group-title {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8; /* slate-400 */
            font-weight: 600;
        }
        .admin-sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }
        .admin-sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
            border-left-color: #14b8a6;
        }
        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background-color: #14b8a6;
            border-left-color: #0d9488;
        }
        .admin-sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }
        /* Main Content Styling */
        .admin-main {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .admin-header {
            background-color: #ffffff;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            z-index: 1030;
        }
        .admin-content {
            padding: 2rem;
            flex-grow: 1;
        }
        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
        .btn-primary {
            background-color: #14b8a6;
            border-color: #14b8a6;
        }
        .btn-primary:hover {
            background-color: #0d9488;
            border-color: #0d9488;
        }
        .badge-teal {
            background-color: #ccfbf1;
            color: #115e59;
        }
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            Swasthya<span>Search</span>
        </a>

        <div class="nav-group-title">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>

        <div class="nav-group-title">Directory Management</div>
        <a href="{{ route('admin.hospitals') }}" class="nav-link {{ request()->routeIs('admin.hospitals') ? 'active' : '' }}">
            <i class="fa-solid fa-hospital"></i> Hospitals
        </a>
        <a href="{{ route('admin.doctors') }}" class="nav-link {{ request()->routeIs('admin.doctors') ? 'active' : '' }}">
            <i class="fa-solid fa-user-doctor"></i> Doctors
        </a>

        <div class="nav-group-title">Taxonomy & AI Matching</div>
        <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->routeIs('admin.departments') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> Departments
        </a>
        <a href="{{ route('admin.diseases') }}" class="nav-link {{ request()->routeIs('admin.diseases') ? 'active' : '' }}">
            <i class="fa-solid fa-tag"></i> Diseases / Symptoms
        </a>

        <div class="nav-group-title">Content Management</div>
        <a href="{{ route('admin.articles') }}" class="nav-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}">
            <i class="fa-solid fa-file-lines"></i> Articles
        </a>
        <a href="{{ route('admin.faqs') }}" class="nav-link {{ request()->routeIs('admin.faqs') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-question"></i> FAQs
        </a>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Top Navbar -->
        <header class="admin-header">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="d-none d-lg-block fw-bold text-secondary">
                SwasthyaSearch Administration Portal
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-circle-user fs-4 text-primary"></i>
                        <span>{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <strong>Please correct the errors below:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
