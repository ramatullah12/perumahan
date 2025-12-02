<x-app-layout>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

        /* STAT BOX */
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }
        .stat-icon {
            font-size: 30px;
            padding: 13px;
            border-radius: 10px;
        }

        .blue   { background:#eaf0ff; color:#3d7dff; }
        .green  { background:#e5ffe9; color:#27a745; }
        .orange { background:#fff1e2; color:#ff9100; }
        .purple { background:#f3e5ff; color:#b455ff; }

        /* APPROVAL BOX */
        .approval-box {
            background: #fff8ef;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #ffe6c8;
        }

        .approval-container {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        }

        /* ACTIVITY */
        .activity-item {
            background: white;
            padding: 14px 18px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            border-left: 4px solid #0d6efd;
        }

        .activity-icon {
            font-size: 18px;
            margin-right: 14px;
            color: #0d6efd;
        }

    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="top-nav d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center">
            <a class="menu-item active" href="#">Dashboard</a>
            <a class="menu-item" href="#">Analisis penjualan</a>
            <a class="menu-item" href="#">Proses pembangunan</a>
        </div>
    </div>

    <div class="container mt-4">
        @yield('content')
    </div>

</x-app-layout>
