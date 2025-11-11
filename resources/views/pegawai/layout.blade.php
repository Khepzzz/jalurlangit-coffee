<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Pegawai')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .active-link {
            background-color: rgb(0, 123, 255);
            color: white !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
        }

        .active-link:hover {
            background-color: rgb(0, 102, 204);
        }

        /* Header has fixed height to calculate correct top position */
        header {
            height: 64px;
        }

        .sidebar {
            position: fixed;
            top: 64px; /* Match header height */
            left: 0;
            height: calc(100vh - 64px); /* Subtract header height */
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 40;
            width: 16rem;
        }

        /* Hide sidebar by default on mobile */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            
            .sidebar.sidebar-open {
                transform: translateX(0);
            }

            .overlay {
                display: none;
                position: fixed;
                top: 64px; /* Match header height */
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 30;
            }

            .overlay.active {
                display: block;
            }
        }

        .sidebar-item {
            transition: all 0.3s ease-in-out;
            border-radius: 8px;
        }

        .sidebar-item:hover {
            background-color: rgba(71, 85, 105, 0.1);
            transform: translateX(5px);
        }

        .bg-gradient {
            background: linear-gradient(135deg, #0d3b58, #122b48);
        }
        
        .shadow-card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .hover\:bg-blue-500:hover {
            background-color: rgba(0, 123, 255, 0.2);
        }

        /* Adjust main content based on sidebar state */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 0; /* No margin when sidebar is hidden */
                transition: margin-left 0.3s ease;
            }

            .main-content.sidebar-open {
                margin-left: 16rem; /* Shift right when sidebar is visible */
            }
        }

        /* Footer adjustments */
        @media (min-width: 768px) {
            footer {
                padding-left: 0;
                width: 100%;
                transition: padding-left 0.3s ease;
            }

            footer.sidebar-open {
                padding-left: 16rem;
            }
        }

        /* Remove top padding from sidebar since we adjusted its position */
        .sidebar .py-20 {
            padding-top: 1.5rem;
            padding-bottom: 6rem; /* Ensure content doesn't get hidden by footer */
        }

        /* Ensure main content is not overlapped by sidebar on mobile */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen overflow-x-hidden">
    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="overlay"></div>

    <!-- Header -->
    <header class="bg-white shadow-card fixed top-0 left-0 w-full flex items-center justify-between px-6 py-4 z-50">
        <div class="flex items-center space-x-3">
            <button id="toggleSidebar" class="text-gray-700 focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
            <h1 class="font-semibold text-xl md:text-3xl text-gray-800">@yield('title', 'Dashboard Pegawai')</h1>
        </div>
    </header>

    <div class="flex pt-16 overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-gray-800 text-white space-y-6 py-6 px-4 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">DASBOARD PEGAWAI</h2>
            </div>

            <!-- Menu -->
            <nav>
                <ul class="space-y-4">
                    <li><a href="{{ route('pegawai.dashboard') }}"
                            class="sidebar-item flex items-center gap-4 py-3 px-5 {{ request()->routeIs('pegawai.dashboard') ? 'active-link' : '' }}">
                            <i class="fa-solid fa-house text-xl"></i> Dashboard
                        </a></li>
                    <li><a href="{{ route('pegawai.produk.index') }}"
                            class="sidebar-item flex items-center gap-4 py-3 px-5 {{ request()->routeIs('pegawai.produk.*') ? 'active-link' : '' }}">
                            <i class="fa-solid fa-box-open text-xl"></i> Kelola Produk
                        </a></li>
                    <li><a href="{{ route('pegawai.pesanan.index') }}"
                            class="sidebar-item flex items-center gap-4 py-3 px-5 {{ request()->routeIs('pegawai.pesanan.*') ? 'active-link' : '' }}">
                            <i class="fa-solid fa-receipt text-xl"></i> Kelola Pesanan
                        </a></li>
                    <li><a href="{{ route('pegawai.ulasan.index') }}"
                            class="sidebar-item flex items-center gap-4 py-3 px-5 {{ request()->routeIs('pegawai.ulasan.*') ? 'active-link' : '' }}">
                            <i class="fa-solid fa-comments text-xl"></i> Kelola Ulasan
                        </a></li>
                    <li><a href="{{ route('pegawai.laporan.index') }}"
                            class="sidebar-item flex items-center gap-4 py-3 px-5 {{ request()->routeIs('pegawai.laporan.*') ? 'active-link' : '' }}">
                            <i class="fa-solid fa-file-alt text-xl"></i> Laporan
                        </a></li>
                    <li>
                        <button id="logoutButton"
                            class="flex items-center gap-4 w-full text-left py-3 px-5 rounded-lg hover:bg-red-500 bg-red-400 transition-all shadow-card">
                            <i class="fa-solid fa-right-from-bracket text-xl"></i> Logout
                        </button>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main id="mainContent" class="main-content flex-1 bg-gray-100 min-h-[calc(100vh-64px-48px)] p-6 overflow-y-auto transition-all">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer id="footer" class="bg-white shadow-card text-center py-4 fixed bottom-0 w-full z-10">
        <p class="text-sm text-gray-600">© {{ date('Y') }} Jalur Langit Coffee. All rights reserved.</p>
    </footer>

    <!-- Modal Logout -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl p-6 w-96 space-y-5 shadow-xl border border-gray-200">
            <div class="flex flex-col items-center">
                <div class="bg-red-100 text-red-600 rounded-full p-3 mb-2 shadow-inner">
                    <i class="fa-solid fa-circle-exclamation fa-2x"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800 text-center">Keluar dari Akun?</h2>
                <p class="text-sm text-gray-500 text-center mt-1">Apakah Anda yakin ingin keluar dari akun ini?</p>
            </div>
            <div class="flex justify-center gap-6 pt-3">
                <button id="cancelLogout" class="px-6 py-2 rounded-lg border border-gray-400 text-gray-600 hover:bg-gray-100 transition-all">Batal</button>
                <form method="POST" action="{{ route('pegawai.logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-all">Ya, Keluar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const footer = document.getElementById('footer');
            const logoutButton = document.getElementById('logoutButton');
            const logoutModal = document.getElementById('logoutModal');
            const cancelLogout = document.getElementById('cancelLogout');
            const overlay = document.getElementById('sidebarOverlay');

            // Check if sidebar should be open or closed based on screen size
            function checkScreenSize() {
                if (window.innerWidth < 768) {
                    // Mobile: Sidebar hidden by default
                    sidebar.classList.remove('sidebar-open');
                    overlay.classList.remove('active');
                    mainContent.classList.remove('sidebar-open');
                    footer.classList.remove('sidebar-open');
                } else {
                    // Desktop: Sidebar visible by default
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    mainContent.classList.add('sidebar-open');
                    footer.classList.add('sidebar-open');
                }
            }

            // Initial check
            checkScreenSize();

            // Toggle sidebar on button click
            toggleSidebar?.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    // Mobile view: Slide sidebar in/out
                    sidebar.classList.toggle('sidebar-open');
                    overlay.classList.toggle('active');
                } else {
                    // Desktop view: Toggle sidebar visibility and shift content
                    sidebar.classList.toggle('translate-x-0');
                    sidebar.classList.toggle('-translate-x-full');
                    mainContent.classList.toggle('sidebar-open');
                    footer.classList.toggle('sidebar-open');
                }
            });

            // Close sidebar when clicking overlay (mobile only)
            overlay?.addEventListener('click', function() {
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('active');
            });

            // Handle window resize
            window.addEventListener('resize', checkScreenSize);

            // Logout modal functionality
            logoutButton?.addEventListener('click', function() {
                logoutModal.classList.remove('hidden');
            });

            cancelLogout?.addEventListener('click', function() {
                logoutModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            logoutModal?.addEventListener('click', function(e) {
                if (e.target === logoutModal) {
                    logoutModal.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>