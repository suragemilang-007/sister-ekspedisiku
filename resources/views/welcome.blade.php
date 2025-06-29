<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EkspedisiKu - Solusi Pengiriman Terpercaya</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-light);
        }

        .hero-section {
            background: var(--accent-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: var(--text-light);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-blue);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .feature-icon:hover {
            transform: translateY(-5px);
        }

        .auth-buttons .btn {
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .navbar {
            background-color: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            color: var(--text-light) !important;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--accent-blue) !important;
        }

        .navbar-toggler {
            border-color: var(--border-color);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28248, 250, 252, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .features-section {
            padding: 5rem 0;
            background-color: var(--primary-medium);
        }

        .about-section {
            padding: 5rem 0;
            background-color: var(--primary-dark);
        }

        .contact-section {
            padding: 5rem 0;
            background-color: var(--primary-medium);
        }

        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
        }

        .btn-outline-primary {
            color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .btn-light {
            background-color: var(--text-light);
            color: var(--primary-dark);
            border: none;
        }

        .btn-light:hover {
            background-color: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-outline-light:hover {
            background-color: rgba(248, 250, 252, 0.1);
            border-color: var(--text-light);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .feature-card {
            background-color: var(--primary-light);
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.2);
        }

        .about-card {
            background-color: var(--primary-medium);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .contact-form {
            background-color: var(--primary-light);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .form-control {
            background-color: var(--primary-medium);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            border-radius: 10px;
        }

        .form-control:focus {
            background-color: var(--primary-medium);
            border-color: var(--accent-blue);
            color: var(--text-light);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .contact-info {
            background-color: var(--primary-light);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--border-color);
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            color: var(--accent-blue);
            font-size: 1.5rem;
            margin-right: 1rem;
            width: 30px;
        }

        .stats-section {
            background: var(--accent-gradient);
            padding: 3rem 0;
            margin: 3rem 0;
            border-radius: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--text-light);
        }

        .stat-label {
            color: rgba(248, 250, 252, 0.8);
            font-size: 1.1rem;
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-gradient);
            border-radius: 2px;
        }

        .smooth-scroll {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="smooth-scroll">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-truck me-2"></i>EkspedisiKu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
                <div class="ms-lg-3 mt-3 mt-lg-0 d-flex gap-2">
                    <a href="/login" class="btn btn-outline-primary">Masuk</a>
                    <a href="/register" class="btn btn-primary">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Kirim Paket dengan Cepat & Aman</h1>
                    <p class="lead mb-4">Solusi pengiriman terpercaya untuk bisnis dan personal. Lacak kiriman Anda secara real-time dengan mudah.</p>
                    <div class="auth-buttons">
                        <a href="/register" class="btn btn-light me-3">Mulai Sekarang</a>
                        <a href="#features" class="btn btn-outline-light">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <i class="fas fa-truck-fast" style="font-size: 15rem; color: rgba(248, 250, 252, 0.3);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Mengapa Memilih EkspedisiKu?</h2>
                <p class="text-muted">Nikmati berbagai keunggulan layanan kami</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-shipping-fast feature-icon"></i>
                        <h4 class="mb-3">Pengiriman Cepat</h4>
                        <p class="text-muted">Jaminan pengiriman tepat waktu ke tujuan dengan layanan ekspres terdepan</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-map-marked-alt feature-icon"></i>
                        <h4 class="mb-3">Lacak Real-time</h4>
                        <p class="text-muted">Pantau status pengiriman Anda secara real-time dengan notifikasi otomatis</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <h4 class="mb-3">Aman & Terpercaya</h4>
                        <p class="text-muted">Jaminan keamanan dan asuransi untuk setiap paket yang Anda kirimkan</p>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">50K+</div>
                                <div class="stat-label">Paket Terkirim</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">1+</div>
                                <div class="stat-label">Kecamatan Terjangkau</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">99.9%</div>
                                <div class="stat-label">Tingkat Kegagalan</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-number">25/7</div>
                                <div class="stat-label">Customer Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Tentang EkspedisiKu</h2>
                <p class="text-muted">Mengenal lebih dekat layanan pengiriman terdepan di Indonesia</p>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-card">
                        <h3 class="mb-4 text-primary">Visi & Misi Kami</h3>
                        <div class="mb-4">
                            <h5 class="text-info mb-3"><i class="fas fa-eye me-2"></i>Visi</h5>
                            <p class="text-muted">Menjadi platform pengiriman terdepan di Indonesia yang menghubungkan setiap sudut nusantara dengan layanan yang cepat, aman, dan terpercaya.</p>
                        </div>
                        <div>
                            <h5 class="text-info mb-3"><i class="fas fa-bullseye me-2"></i>Misi</h5>
                            <ul class="text-muted">
                                <li>Memberikan layanan pengiriman berkualitas tinggi</li>
                                <li>Menggunakan teknologi terdepan untuk tracking real-time</li>
                                <li>Membangun kepercayaan melalui transparansi dan kehandalan</li>
                                <li>Mendukung pertumbuhan ekonomi digital Indonesia</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-card">
                        <h3 class="mb-4 text-primary">Mengapa EkspedisiKu?</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <h6>Pengalaman 10+ Tahun</h6>
                                        <p class="text-muted small mb-2">Berpengalaman melayani jutaan pengiriman di seluruh Indonesia</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <h6>Jaringan Luas</h6>
                                        <p class="text-muted small mb-2">Mencakup 34 provinsi dengan 1000+ kota dan kabupaten</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <h6>Teknologi Modern</h6>
                                        <p class="text-muted small mb-2">Sistem tracking canggih dan aplikasi mobile yang user-friendly</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <h6>Harga Kompetitif</h6>
                                        <p class="text-muted small mb-2">Tarif terjangkau dengan kualitas layanan premium</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Hubungi Kami</h2>
                <p class="text-muted">Siap membantu Anda 24/7 untuk kebutuhan pengiriman</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <h4 class="mb-4">Kirim Pesan</h4>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="nama@email.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subjek</label>
                                    <select class="form-control">
                                        <option>Pertanyaan Umum</option>
                                        <option>Keluhan Layanan</option>
                                        <option>Kerjasama Bisnis</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Pesan</label>
                                    <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-info">
                        <h4 class="mb-4">Informasi Kontak</h4>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <div>
                                <h6>Alamat Kantor</h6>
                                <p class="text-muted mb-0">Jl. Letjend Pol. Soemarto No.127
                                    <br>Kabupaten Banyumas, Jawa Tengah 53127</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <div>
                                <h6>Telepon</h6>
                                <p class="text-muted mb-0">+62 857-2686-3801</p>
                                <p class="text-muted mb-0">+62 812-2972-8969</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope contact-icon"></i>
                            <div>
                                <h6>Email</h6>
                                <p class="text-muted mb-0">tri.ant@ekspedisiku.com</p>
                                <p class="text-muted mb-0">nova@ekspedisiku.com</p>
                                <p class="text-muted mb-0">erik@ekspedisiku.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock contact-icon"></i>
                            <div>
                                <h6>Jam Operasional</h6>
                                <p class="text-muted mb-0">Senin - Sabtu: 00:00 - 23:55<br>Minggu: 00:30 - 23:00</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-headset contact-icon"></i>
                            <div>
                                <h6>Customer Service</h6>
                                <p class="text-muted mb-0">24/7 via WhatsApp<br>+62 852-9142-6243</p>
                            </div>
                        </div>
                        
                        <hr class="my-4" style="border-color: var(--border-color);">
                        
                        <h6 class="mb-3">Ikuti Kami</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-decoration-none">
                                <i class="fab fa-facebook-f" style="color: var(--accent-blue); font-size: 1.5rem;"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="fab fa-twitter" style="color: var(--accent-blue); font-size: 1.5rem;"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="fab fa-instagram" style="color: var(--accent-blue); font-size: 1.5rem;"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="fab fa-linkedin-in" style="color: var(--accent-blue); font-size: 1.5rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4" style="background-color: var(--primary-dark); border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; 2025 EkspedisiKu. Semua hak dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Syarat & Ketentuan</a>
                    <a href="#" class="text-muted text-decoration-none">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS for smooth scrolling -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(15, 23, 42, 0.98)';
            } else {
                navbar.style.backgroundColor = 'rgba(15, 23, 42, 0.95)';
            }
        });
    </script>
</body>
</html>