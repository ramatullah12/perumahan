<x-app-layout>
    <style>
        body {
            background: #f5f7fb;
            font-family: "Poppins", sans-serif;
        }

        /* NAVBAR */
        .top-nav {
            background: white;
            padding: 14px 25px;
            border-bottom: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-item {
            margin-right: 26px;
            font-weight: 500;
            color: #555;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu-item:hover, .menu-item.active {
            color: #0d6efd;
            text-decoration: none;
        }

        .menu-item.active {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 6px;
        }

        /* STAT BOX */
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            height: 100%;
            border: 1px solid #f1f5f9;
        }

        .stat-icon {
            font-size: 24px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .blue   { background:#eaf0ff; color:#3d7dff; }
        .green  { background:#e5ffe9; color:#27a745; }
        .orange { background:#fff1e2; color:#ff9100; }
        .purple { background:#f3e5ff; color:#b455ff; }

        .admin-container { padding-bottom: 40px; }
    </style>

    <div class="top-nav">
        <div class="d-flex align-items-center">
            {{-- Sesuaikan route dengan web.php Anda --}}
            <a class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a>
            <a class="menu-item {{ request()->is('admin/booking*') ? 'active' : '' }}" href="{{ route('admin.booking.index') }}">Booking Management</a>
            <a class="menu-item {{ request()->is('admin/project*') ? 'active' : '' }}" href="{{ route('admin.project.index') }}">Manajemen Proyek</a>
            <a class="menu-item {{ request()->is('admin/tipe*') ? 'active' : '' }}" href="{{ route('admin.tipe.index') }}">Manajemen Tipe Rumah</a>
            <a class="menu-item {{ request()->is('admin/unit*') ? 'active' : '' }}" href="{{ route('admin.unit.index') }}">Manajemen Unit</a>
            <a class="menu-item {{ request()->is('admin/progres*') ? 'active' : '' }}" href="{{ route('admin.progres.index') }}">Update Progres</a>
            <a class="menu-item {{ request()->is('admin/laporan*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">Laporan</a>
        </div>
    </div>

    <div class="container admin-container mt-4">
        @yield('content')
    </div>
</x-app-layout>