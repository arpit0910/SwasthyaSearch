<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>SwasthyaSearch Admin Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Typography aligned with public website -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
    <style>
        :root {
            --ss-bg: #f1f5f9;
            --ss-surface: #ffffff;
            --ss-text: #0f172a;
            --ss-muted: #64748b;
            --ss-primary: #14b8a6;
            --ss-primary-dark: #0f766e;
            --ss-indigo: #312e81;
            --ss-navy: #0b1220;
            --ss-border: #dbe7f3;
        }
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(900px 500px at 95% -10%, rgba(20,184,166,0.13), transparent 60%),
                radial-gradient(700px 450px at -10% 20%, rgba(49,46,129,0.12), transparent 60%),
                var(--ss-bg);
            color: var(--ss-text);
            overflow-x: hidden;
        }
        /* Sidebar Styling */
        .admin-sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0b1220 0%, #101a33 60%, #131d3a 100%);
            color: #cbd5e1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.3s ease;
            box-shadow: 10px 0 30px rgba(2, 6, 23, 0.3);
        }
        .admin-sidebar .sidebar-brand {
            padding: 1.5rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .admin-sidebar .sidebar-brand span {
            color: #2dd4bf;
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
            background-color: rgba(45, 212, 191, 0.12);
            border-left-color: #2dd4bf;
        }
        .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(20, 184, 166, 0.9) 0%, rgba(15, 118, 110, 0.95) 100%);
            border-left-color: #5eead4;
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            border-bottom: 1px solid var(--ss-border);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
            z-index: 1030;
        }
        .admin-content {
            padding: 2rem;
            flex-grow: 1;
        }
        /* Card Styling */
        .card {
            border: 1px solid var(--ss-border);
            border-radius: 1rem;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }
        .card:hover {
            transform: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid var(--ss-border);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
        .btn-primary {
            background: linear-gradient(90deg, var(--ss-primary) 0%, var(--ss-primary-dark) 100%);
            border-color: var(--ss-primary-dark);
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.25);
            font-weight: 700;
        }
        .btn-primary:hover {
            filter: brightness(0.97);
            border-color: var(--ss-primary-dark);
        }
        .btn-outline-primary {
            border-color: #99f6e4;
            color: var(--ss-primary-dark);
        }
        .btn-outline-primary:hover {
            background: rgba(45, 212, 191, 0.14);
            color: #0f172a;
            border-color: #2dd4bf;
        }
        .badge-teal {
            background-color: #ccfbf1;
            color: #115e59;
        }
        .table {
            --bs-table-hover-bg: #f0fdfa;
            --bs-table-striped-bg: #f8fafc;
        }
        .table-light {
            --bs-table-bg: #f8fafc;
        }
        .dropdown-menu {
            border-radius: 0.9rem;
            border: 1px solid var(--ss-border);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }
        .alert {
            border-radius: 0.9rem;
            border-width: 1px;
        }
        /* Standardize row action buttons (Edit/Delete) on admin list tables. */
        .admin-content td.text-end {
            white-space: nowrap;
        }
        .admin-content td.text-end .btn.btn-sm {
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.55rem;
            padding: 0 0.6rem;
            line-height: 1;
            vertical-align: middle;
        }
        .admin-content td.text-end form.d-inline {
            display: inline-block !important;
            margin: 0;
            vertical-align: middle;
        }
        .admin-content td.text-end a.btn + form.d-inline,
        .admin-content td.text-end form.d-inline + a.btn {
            margin-left: 0.35rem;
        }
        .admin-content td.text-end .fa-pen-to-square,
        .admin-content td.text-end .fa-trash {
            font-size: 0.9rem;
        }
        .department-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border: 1px solid #99f6e4;
            background: #f0fdfa;
            color: #115e59;
            border-radius: 999px;
            padding: 0.25rem 0.6rem;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .department-pill button {
            border: 0;
            background: transparent;
            color: #0f766e;
            line-height: 1;
            padding: 0;
            font-size: 0.9rem;
            cursor: pointer;
        }
        /* Keep admin forms fully static (no hover/focus motion). */
        .admin-content form,
        .admin-content form * {
            animation: none !important;
            transition: none !important;
        }
        /* Prevent modal flicker on open/close in heavy admin forms */
        .modal,
        .modal-dialog,
        .modal-content {
            backface-visibility: hidden;
            transform: translateZ(0);
        }
        .modal .modal-content {
            will-change: transform, opacity;
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
        <a href="{{ route('admin.blood_banks') }}" class="nav-link {{ request()->routeIs('admin.blood_banks') ? 'active' : '' }}">
            <i class="fa-solid fa-droplet"></i> Blood Banks
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
        <a href="{{ route('admin.general_qa') }}" class="nav-link {{ request()->routeIs('admin.general_qa*') ? 'active' : '' }}">
            <i class="fa-solid fa-notes-medical"></i> General Medical Q&A
        </a>
        <a href="{{ route('admin.cached_medical_questions') }}" class="nav-link {{ request()->routeIs('admin.cached_medical_questions*') ? 'active' : '' }}">
            <i class="fa-solid fa-database"></i> Cached Medical Questions
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-department-picker]').forEach(function (picker) {
                if (picker.dataset.initialized === '1') {
                    return;
                }
                picker.dataset.initialized = '1';

                var hiddenId = picker.getAttribute('data-hidden-select');
                var pillsId = picker.getAttribute('data-pills-container');
                var hidden = document.getElementById(hiddenId);
                var pills = document.getElementById(pillsId);

                if (!hidden || !pills) {
                    return;
                }

                function renderPills() {
                    pills.innerHTML = '';
                    Array.from(hidden.options).forEach(function (option) {
                        if (!option.selected) {
                            return;
                        }
                        var pill = document.createElement('span');
                        pill.className = 'department-pill';
                        pill.innerHTML = '<span>' + option.text + '</span>';

                        var remove = document.createElement('button');
                        remove.type = 'button';
                        remove.setAttribute('aria-label', 'Remove department');
                        remove.textContent = 'x';
                        remove.addEventListener('click', function () {
                            option.selected = false;
                            renderPills();
                        });

                        pill.appendChild(remove);
                        pills.appendChild(pill);
                    });
                }

                picker.addEventListener('change', function () {
                    var selectedValue = picker.value;
                    if (!selectedValue) {
                        return;
                    }
                    var target = hidden.querySelector('option[value="' + selectedValue.replace(/"/g, '\\"') + '"]');
                    if (target) {
                        target.selected = true;
                    }
                    picker.value = '';
                    renderPills();
                });

                renderPills();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

