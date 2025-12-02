<x-app-layout>

    <style>
        body {
            background: #f5f7fb;
            font-family: "Poppins", sans-serif;
        }

        .top-nav {
            background: white;
            padding: 14px 25px;
            border-bottom: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .menu-item {
            margin-right: 26px;
            font-weight: 500;
            color: #555;
        }

        .menu-item.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 6px;
        }
    </style>

    <!-- NAVBAR -->
    <div class="top-nav d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center">
            <a class="menu-item {{ request()->is('customer/booking') ? 'active' : '' }}" href="/customer/booking">Booking Saya</a>
            <a class="menu-item {{ request()->is('customer/progres*') ? 'active' : '' }}" href="/customer/progres">Proses pembangunan</a>
            <a class="menu-item" href="/customer/proyek">Jelajahi proyek</a>
            <a class="menu-item" href="/customer/notifikasi">Notifikasi</a>
        </div>
    </div>

    <!-- HALAMAN -->
    <div class="container mt-4">
        @yield('content')
    </div>

</x-app-layout>
