<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sister Ekspedisi</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-dark: #0f172a;
            --primary-medium: #1e293b;
            --primary-light: #334155;
            --accent-blue: #0ea5e9;
            --accent-gradient: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --sidebar-width: 300px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            background: var(--primary-dark);
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--primary-medium);
            position: relative;
        }

        .sidebar-header .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar-header .logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-header .brand-text {
            color: var(--text-light);
            font-size: 1.25rem;
            font-weight: 700;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
        }

        .sidebar-toggle {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background: var(--primary-light);
            border: none;
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-toggle:hover {
            background: var(--accent-blue);
            color: white;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1.5rem 1rem;
            height: calc(100vh - 100px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--primary-medium);
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 2px;
        }

        .nav-section-title {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 1.5rem 0 0.75rem 1rem;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.875rem 1rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: rgba(14, 165, 233, 0.1);
            color: var(--accent-blue);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        .nav-link i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .nav-link .nav-text {
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
        }

        /* Notification Badge */
        .notification-badge {
            background: var(--danger);
            color: white;
            border-radius: 10px;
            padding: 0.125rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* User Profile */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid var(--border-color);
            background: var(--primary-medium);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 12px;
            background: rgba(14, 165, 233, 0.1);
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
        }

        .user-name {
            color: var(--text-light);
            font-size: 0.875rem;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Mobile Responsiveness */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .mobile-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1060;
            background: var(--primary-dark);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 1024px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            background: white;
        }

        .card:hover {
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.25rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--accent-gradient);
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-success {
            background: var(--success);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        /* Loading States */
        .loading-spinner {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        /* Tooltip Customization */
        .tooltip {
            font-size: 0.75rem;
        }

        .tooltip-inner {
            background: var(--primary-dark);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="brand-text">
                    @if(Session::get('user_role') === 'pelanggan')
                    <a href="{{ url('/dashboard/pengirim') }}" class="text-decoration-none text-white fw-bold">
                        Ekspedisiku
                    </a>
                    @elseif(Session::get('user_role') === 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none text-white fw-bold">
                        Ekspedisiku
                    </a>
                    @elseif(Session::get('user_role') === 'kurir')
                    <a href="{{ url('/kurir/dashboard') }}" class="text-decoration-none text-white fw-bold">
                        Ekspedisiku
                    </a>
                    @else
                    <a href="{{ url('/') }}" class="text-decoration-none text-white fw-bold">
                        Ekspedisiku
                    </a>
                    @endif
                </div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle" data-bs-toggle="tooltip" data-bs-placement="right" title="Toggle Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>
            
            @if(Session::get('user_role') === 'pelanggan')
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link {{ Request::is('dashboard/pengirim/feedbacka*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Lacak Paket" onclick="showLacakModal()">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="nav-text">Lacak Paket</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/pengirim/feedback" class="nav-link {{ Request::is('dashboard/pengirim/feedback*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Feedback Rating">
                        <i class="fas fa-star"></i>
                        <span class="nav-text">Feedback Rating</span>
                        <span class="nav-text" id="feedbackCount">0</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/dashboard/pengirim/history" class="nav-link {{ Request::is('dashboard/pengirim/history*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="History Pengiriman">
                        <i class="fas fa-history"></i>
                        <span class="nav-text">History Pengiriman</span>
                    </a>
                </li>
            </ul>

            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/dashboard/pengirim/kirim" class="nav-link {{ Request::is('alamat-tujuan.index') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Form Pengiriman Baru">
                        <i class="fas fa-box"></i>
                        <span class="nav-text">Pengiriman Baru</span>
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/dashboard/pengirim/alamat-tujuan" class="nav-link {{ Request::is('dashboard/pengirim/alamat-tujuan*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Form Pengiriman Baru">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span class="nav-text">Alamat Tujuan</span>
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/alamat-penjemputan" class="nav-link {{ Request::is('/alamat-penjemputan*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Form Pengiriman Baru">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span class="nav-text">Alamat Penjemputan</span>
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/pengguna/edit" class="nav-link {{ Request::is('pengguna/edit*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan Akun">
                        <i class="fas fa-user-cog"></i>
                        <span class="nav-text">Pengaturan Akun</span>
                    </a>
                </li>
            </ul>
            @endif
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer d-flex align-items-center justify-content-between px-3 py-2">
            <div class="user-profile d-flex align-items-center">
                <div class="user-avatar me-2">
                    <i class="fas fa-user fa-lg"></i>
                </div>
                <div class="user-info">
                    <div class="user-name fw-semibold">{{ Session::get('user_name') ?? 'User' }}</div>
                    <div class="user-role" style="font-size: 0.85rem;">
                        @if(Session::get('user_role') === 'pelanggan')
                            Pengirim
                        @elseif(Session::get('user_role') === 'admin')
                            Admin
                        @elseif(Session::get('user_role') === 'kurir')
                            Kurir
                        @else
                            User
                        @endif
                    </div>
                </div>
                <div>
                <form action="{{ route('logout') }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
                
            </div>
            
        </div>

    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.socket.io/4.3.2/socket.io.min.js"></script>
    <!-- Custom JS -->
    <script>
        const socket = io("http://localhost:4000");

        socket.on("update-data-pengiriman", function (data) {
            loadSidebar(); 
        });

         function loadSidebar() {
            $.ajax({
                url: "{{ route('dashboard.history.feedbackCount') }}",
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    console.log(res);
                    if (res.stats.total_pengirimanDenganFeedback === 0) {
                        $('#feedbackCount').text('');
                    } else {
                        $('#feedbackCount').text(res.stats.total_pengirimanDenganFeedback);
                    }
                    // $('#feedbackCount').text(res.stats.total_pengirimanDenganFeedback);
                    
                }});
            }
            $(document).ready(function () {
                

                loadSidebar(); 
            });



        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileOverlay = document.getElementById('mobileOverlay');

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

           
            
            
            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Update icon
                    const icon = this.querySelector('i');
                    if (icon) {
                        if (sidebar.classList.contains('collapsed')) {
                            icon.classList.remove('fa-chevron-left');
                            icon.classList.add('fa-chevron-right');
                            this.setAttribute('title', 'Expand Sidebar');
                        } else {
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-left');
                            this.setAttribute('title', 'Collapse Sidebar');
                        }
                        
                        // Update tooltips
                        const tooltipInstance = bootstrap.Tooltip.getInstance(this);
                        if (tooltipInstance) {
                            tooltipInstance.dispose();
                        }
                        new bootstrap.Tooltip(this);
                    }
                });
            }

            // Mobile menu toggle
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.add('show');
                    mobileOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Close mobile menu
            function closeMobileMenu() {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu on navigation
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        closeMobileMenu();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    closeMobileMenu();
                }
            });

            // Add loading state to forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    if (button && !button.disabled) {
                        button.disabled = true;
                        const originalText = button.innerHTML;
                        button.innerHTML = `
                            <span class="spinner-border spinner-border-sm loading-spinner me-2" role="status" aria-hidden="true"></span>
                            Loading...
                        `;
                        
                        // Re-enable button after 5 seconds as fallback
                        setTimeout(() => {
                            if (button) {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        }, 5000);
                    }
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Auto-hide tooltips on mobile
            if (window.innerWidth <= 768) {
                tooltipList.forEach(tooltip => {
                    tooltip.disable();
                });
            }
        });
    </script>
    
    @stack('scripts')
    @yield('scripts')
</body>
@include('dashboard_pengirim.modal_lacakpaket')
</html>