<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jalur Langit Coffee')</title>

    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&family=Nunito+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #51392c;
            --primary-dark: #362319;
            --secondary-color: #d4b08c;
            --secondary-dark: #c09370;
            --accent-color: #8e6c5c;
            --background-color: #f9f5f1;
            --text-color: #362319;
            --light-text: #f9f5f1;
        }
        
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: var(--background-color);
            color: var(--text-color);
            padding-top: 80px;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Lora', serif;
        }

        /* Navbar & Footer */
        .navbar, footer {
            background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
            color: var(--light-text);
        }
        
        .navbar {
            padding: 16px 0;
            transition: all 0.3s ease;
            border-bottom: 3px solid var(--secondary-color);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.8);
            padding: 4px 8px;
            transition: all 0.2s ease;
        }

        .navbar-toggler:focus,
        .navbar-toggler:active,
        .navbar-toggler:hover {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(212, 176, 140, 0.5) !important;
            border-color: var(--secondary-color) !important;
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            font-family: 'Lora', serif;
            color: var(--light-text) !important;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            font-weight: 500;
            transition: 0.3s;
            margin: 0 8px;
            padding: 6px 12px;
            border-radius: 8px;
            position: relative;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link:focus {
            box-shadow: 0 0 0 3px rgba(212, 176, 140, 0.5);
        }
        
        .nav-link.active {
            color: var(--secondary-color) !important;
            font-weight: 600;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 12px;
            right: 12px;
            height: 2px;
            background-color: var(--secondary-color);
        }
        
        .nav-item {
            display: flex;
            align-items: center;
        }

        /* Responsive spacing for navbar items */
        @media (max-width: 991.98px) {
            .navbar-nav .nav-item {
                margin-bottom: 12px;
            }

            .navbar-nav .nav-item:last-child {
                margin-bottom: 0;
            }
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(90deg, var(--secondary-color), var(--secondary-dark));
            color: var(--text-color);
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(90deg, var(--secondary-dark), #b47a30);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            color: var(--text-color);
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(212, 176, 140, 0.4), 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        
        .btn-secondary {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            color: var(--light-text);
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .btn-secondary:hover {
            background: linear-gradient(90deg, #435e54, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            color: var(--light-text);
        }

        .btn-outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            transition: 0.3s;
            border-radius: 25px;
            margin: 0 5px;
            background: transparent;
        }

        .btn-outline:hover,
        .btn-outline.active {
            background: var(--primary-color);
            color: var(--light-text);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3') no-repeat center center/cover;
            padding: 120px 0;
            text-align: center;
            color: var(--light-text);
            position: relative;
            margin-bottom: 60px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 5px solid var(--secondary-color);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
            margin-bottom: 1.5rem;
            font-family: 'Lora', serif;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.6);
            line-height: 1.6;
        }
        
        /* Card Produk */
        .card {
            border-radius: 15px;
            transition: 0.3s;
            overflow: hidden;
            height: 100%;
            border: none;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 1.8rem;
        }

        .card-title {
            font-weight: 600;
            font-family: 'Lora', serif;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }

        .card-text {
            color: #555;
            line-height: 1.6;
        }

        .produk-img {
            height: 240px;
            object-fit: cover;
            transition: 0.5s;
        }
        
        .card:hover .produk-img {
            transform: scale(1.05);
        }

        .sold-out-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        /* Badge/Label */
        .badge {
            padding: 8px 12px;
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: 20px;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
            color: var(--light-text);
        }
        
        .badge-secondary {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        /* Alert */
        .alert {
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        
        .alert-info {
            background-color: rgba(81, 57, 44, 0.1);
            color: var(--primary-dark);
            border-left: 4px solid var(--primary-color);
        }

        /* Footer */
        footer {
            text-align: center;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            padding: 50px 0 20px;
            margin-top: 80px;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.05);
        }
        
        footer h5 {
            color: var(--secondary-color);
            margin-bottom: 1.2rem;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        footer p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        footer hr {
            background-color: rgba(255, 255, 255, 0.2);
            margin: 2.5rem 0;
        }
        
        .social-icons a {
            display: inline-block;
            color: var(--light-text);
            margin: 0 10px;
            font-size: 1.2rem;
            transition: 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        /* Pastikan ada ruang antara content dan footer */
        .main-content {
            min-height: calc(100vh - 300px);
            padding: 20px 0 60px;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Section styling */
        .section-title {
            text-align: center;
            font-size: 2.2rem;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            width: 80px;
            height: 3px;
            background: var(--secondary-color);
            transform: translateX(-50%);
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/produk">
                <img src="/storage/logo/logo.png" alt="Jalur Langit Coffee Logo" class="me-2" style="height: 50px; width: auto; display: inline-block; vertical-align: middle;">Jalur Langit Coffee
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse align-items-center" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('produk')) active @endif" href="/produk">
                            <i class="bi bi-cup me-1"></i>Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('pesanan')) active @endif" href="/pesanan">
                            <i class="bi bi-clipboard-check me-1"></i>Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->is('ulasan')) active @endif" href="/ulasan">
                            <i class="bi bi-star me-1"></i>Ulasan
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        @php
                            $token = session('token');
                            $keranjang = session("keranjang_$token");
                        @endphp
                        <a href="/keranjang" class="btn btn-primary position-relative">
                            <i class="bi bi-cart4"></i> Keranjang
                            <span id="keranjang-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $keranjang ? count($keranjang) : 0 }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    @yield('hero')

    <!-- Main Content -->
    <div class="container main-content fade-in">
        @if (session('token') && session('nama_pelanggan'))
            <div class="alert alert-info d-flex justify-content-between align-items-center mb-4">
                <span><i class="bi bi-person-badge me-2"></i><strong>Nama:</strong> {{ session('nama_pelanggan') }}</span>
                <span><i class="bi bi-key me-2"></i><strong>Token:</strong> {{ session('token') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang Kami</h5>
                    <p>Tempat yang aman bagi semua orang untuk berkumpul, berkolaborasi, dan mendapatkan inspirasi.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Jam Buka</h5>
                    <p>Senin - Jumat: 14.00 - 24.00<br>Sabtu - Minggu: 11.00 - 24.00</p>
                </div>
                <div class="col-md-4 mb-4">
    <h5>Hubungi Kami</h5>
    <p>
        <i class="bi bi-geo-alt me-1"></i> Dusun 4, Suwawal, Kec. Mlonggo, Kabupaten Jepara, Jawa Tengah 59452<br>
        <i class="bi bi-whatsapp me-1"></i> <a href="https://wa.me/+6289510780314" class="text-white" target="_blank">0895-1078-0314</a><br>
        <i class="bi bi-envelope me-1"></i> <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=jalurlangitcoffe@gmail.com" class="text-white" target="_blank">jalurlangitcoffe@gmail.com</a>
    </p>
</div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0">© {{ date('Y') }} Jalur Langit Coffee - All Rights Reserved</p>
<div class="social-icons">
    <span class="text-white me-2">jalurlangitcoffe</span>
    <a href="https://www.instagram.com/jalurlangitcoffe?igsh=MWJpOGg5bmFzZzcxZg==" class="text-white me-3" target="_blank"><i class="bi bi-instagram"></i></a>
</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        // Efek scroll untuk navbar
        window.addEventListener('scroll', function() {
            let navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Fix navbar issue when expanded
        document.addEventListener("DOMContentLoaded", function () {
            const navbar = document.querySelector(".navbar-collapse");
            const navLinks = document.querySelectorAll(".nav-link");
            
            // Add click effect to nav links
            navLinks.forEach(link => {
                link.addEventListener("click", function() {
                    // Remove active class from all links
                    navLinks.forEach(item => {
                        if (!item.href.includes(window.location.pathname)) {
                            item.classList.remove("active");
                        }
                    });
                    
                    // Add active class to clicked link
                    if (!this.classList.contains("active")) {
                        this.classList.add("active");
                    }
                });
                
                // Add focus effect
                link.addEventListener("focus", function() {
                    this.style.boxShadow = "0 0 0 3px rgba(212, 176, 140, 0.5)";
                });
                
                link.addEventListener("blur", function() {
                    this.style.boxShadow = "none";
                });
            });
            
            navbar.addEventListener("shown.bs.collapse", function () {
                document.body.style.paddingTop = "280px";
            });
            
            navbar.addEventListener("hidden.bs.collapse", function () {
                document.body.style.paddingTop = "80px";
            });
            
            // Add animation to content
            const mainContent = document.querySelector(".main-content");
            mainContent.classList.add("fade-in");
        });
    </script>
    @yield('scripts')
</body>

</html>