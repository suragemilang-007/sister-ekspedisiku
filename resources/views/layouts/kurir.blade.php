<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Ekspedisiku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-color-dark: #2e59d9;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;

            --primary-bg-light: rgba(78, 115, 223, 0.1);
            --success-bg-light: rgba(28, 200, 138, 0.1);
            --info-bg-light: rgba(54, 185, 204, 0.1);
            --warning-bg-light: rgba(246, 194, 62, 0.1);
            --danger-bg-light: rgba(231, 74, 59, 0.1);

            --sidebar-width: 300px;
            --sidebar-collapsed-width: 80px;

            --font-family-sans-serif: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        body {
            font-family: var(--font-family-sans-serif);
            background-color: #f8f9fc;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, rgb(0, 13, 52) 0%, #224abe 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header .logo-container {
            display: flex;
            align-items: center;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .sidebar-header .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            color: var(--primary-color);
            margin-right: 10px;
            flex-shrink: 0;
        }

        .sidebar-header .brand-text {
            font-weight: 700;
            font-size: 1.2rem;
            transition: opacity 0.3s;
            opacity: 1;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
        }

        .sidebar-toggle {
            background: transparent;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            padding: 0.8rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            transition: all 0.3s;
            position: relative;
            white-space: nowrap;
        }

        .nav-link i {
            min-width: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: margin-right 0.3s;
        }

        .nav-link .nav-text {
            transition: opacity 0.3s;
            opacity: 1;
            margin-left: 0.5rem;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            margin-left: 0;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: white;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 1.5rem;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .user-details {
            overflow: hidden;
            transition: opacity 0.3s;
            opacity: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .user-info {
            justify-content: center;
        }

        .sidebar.collapsed .user-details {
            opacity: 0;
            width: 0;
            margin-left: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left 0.3s;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        .sidebar.collapsed~.main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar~.main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-color-dark);
            border-color: var(--primary-color-dark);
        }

        /* Utility Classes */
        .bg-primary-light {
            background-color: var(--primary-bg-light);
        }

        .bg-success-light {
            background-color: var(--success-bg-light);
        }

        .bg-info-light {
            background-color: var(--info-bg-light);
        }

        .bg-warning-light {
            background-color: var(--warning-bg-light);
        }

        .bg-danger-light {
            background-color: var(--danger-bg-light);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-info {
            color: var(--info-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            font-size: 0.85rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        }

        /* Tooltip */
        .tooltip {
            font-size: 0.75rem;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Overlay for mobile menu */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar.show~.sidebar-overlay {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Sidebar Overlay (for mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="brand-text">
                    <a href="{{ url('/kurir/dashboard') }}" class="text-decoration-none text-white fw-bold">
                        Ekspedisiku
                    </a>

                </div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/kurir/dashboard" class="nav-link {{ Request::is('kurir/dashboard') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                        <i class="fas fa-house"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kurir/tugas" class="nav-link {{ Request::is('kurir/tugas*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Tugas Saya">
                        <i class="fas fa-tasks"></i>
                        <span class="nav-text">Tugas Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kurir/riwayat" class="nav-link {{ Request::is('kurir/riwayat*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Riwayat Pengiriman">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">Riwayat Pengiriman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/kurir/feedback" class="nav-link {{ Request::is('kurir/feedback*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Feedback">
                        <i class="fas fa-star"></i>
                        <span class="nav-text">Feedback</span>
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/kurir/pengaturan" class="nav-link {{ Request::is('kurir/pengaturan*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan Akun">
                        <i class="fas fa-user-cog"></i>
                        <span class="nav-text">Pengaturan Akun</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer d-flex align-items-center justify-content-between px-3 py-2">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Session::get('user_name') }}</div>
                    <div class="user-role">Kurir</div>
                </div>
            </div>
            <div>
                <a href="/logout" class="btn btn-sm btn-outline-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile Menu Toggle -->
        <div class="d-flex justify-content-between align-items-center d-md-none mb-4">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="brand-text fw-bold fs-4">Ekspedisiku</div>
            <div></div> <!-- Empty div for flex spacing -->
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // Get elements
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Check if sidebar state is saved in localStorage
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('collapsed');
            }

            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');

                    // Save state to localStorage
                    if (sidebar.classList.contains('collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                        // Update tooltip
                        this.setAttribute('title', 'Expand Sidebar');
                        bootstrap.Tooltip.getInstance(this).setContent({
                            '.tooltip-inner': 'Expand Sidebar'
                        });
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                        // Update tooltip
                        this.setAttribute('title', 'Collapse Sidebar');
                        bootstrap.Tooltip.getInstance(this).setContent({
                            '.tooltip-inner': 'Collapse Sidebar'
                        });
                    }
                });
            }

            // Mobile menu toggle
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.add('show');
                });
            }

            // Close sidebar when clicking on overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                });
            }

            // Close mobile menu on navigation
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show');
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>