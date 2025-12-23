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
            text-decoration: none; /* Menghilangkan underline default */
            position: relative; /* Penting untuk posisi badge */
        }

        .menu-item.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 6px;
        }

        /* Styling Badge Notifikasi */
        .notif-badge {
            background: #ef4444; /* Warna merah terang */
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 50px;
            position: absolute;
            top: -10px;
            right: -15px;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>

    <div class="top-nav d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a class="menu-item {{ request()->is('customer/booking') ? 'active' : '' }}" href="/customer/booking">Booking Saya</a>
            <a class="menu-item {{ request()->routeIs('customer.progres.*') ? 'active' : '' }}" href="{{ route('customer.progres.index') }}">Proses Pembangunan</a>
            <a class="menu-item {{ request()->is('customer/proyek') ? 'active' : '' }}" href="/customer/proyek">Jelajahi proyek</a>
            
            {{-- Navigasi Notifikasi dengan Badge Angka --}}
            <a class="menu-item {{ request()->is('customer/notifikasi') ? 'active' : '' }}" href="/customer/notifikasi">
                Notifikasi
                {{-- Badge muncul jika ada notifikasi belum dibaca --}}
                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                    <span class="notif-badge">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <div class="container mt-4">
        @yield('content')
    </div>

</x-app-layout>