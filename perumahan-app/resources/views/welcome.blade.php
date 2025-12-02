<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PT Kedaton Sejahtera Abadi</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f7fb;
            font-family: "Poppins", sans-serif;
        }

        /* NAVBAR */
        .navbar-custom {
            background: white;
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .navbar-brand img {
            height: 34px;
        }

        .login-btn {
            background: #1d8afe;
            padding: 6px 16px;
            border-radius: 20px;
            color: white;
            font-size: 14px;
        }

        /* HEADER */
        .hero-header {
            background: #0d6efd;
            padding: 60px 0 120px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            color: white;
        }

        .search-box {
            max-width: 700px;
            margin: auto;
            position: relative;
        }

        .search-box input {
            height: 55px;
            border-radius: 14px;
            padding-left: 50px;
        }

        .search-box i {
            position: absolute;
            top: 17px;
            left: 18px;
            color: #737373;
            font-size: 20px;
        }

        /* STAT CARDS */
        .stats-card {
            background: white;
            padding: 18px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgb(77, 235, 85);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .stats-icon {
            font-size: 32px;
            padding: 10px;
            border-radius: 12px;
        }

        .stats-1 { background: #e7f1ff; color: #0d6efd; }
        .stats-2 { background: #e7fff3; color: #28a745; }
        .stats-3 { background: #fff4e7; color: #ff8800; }

        /* PROPERTY CARDS */
        .property-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        }

        .property-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .status-badge {
            background: #28a745;
            padding: 5px 14px;
            border-radius: 20px;
            color: white;
            font-size: 13px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* CTA SECTION */
        .cta-section {
            background: #eaf2ff;
            padding: 60px 20px;
            text-align: center;
            border-radius: 15px;
            margin-top: 70px;
        }

        .cta-section h5 {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 20px;
        }

        .cta-section p {
            color: #555;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .cta-section .btn {
            padding: 10px 28px;
            border-radius: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.jpg') }}">
        </a>
        <a class="navbar-brand d-flex align-items-center gap-2 mx-auto">
            <span class="fw-bold text-primary">PT Kedaton Sejahtera Abadi</span>
        </a>
        <a href="{{ route('login') }}" class="login-btn">
            <i class="bi bi-box-arrow-in-right"></i> Login
        <a href="{{ route('register') }}" class="Register-btn">
            <i class="bi bi-box-arrow-in-right"></i> Register
        </a>
    </div>
</nav>

<!-- HEADER -->
<div class="hero-header text-center">
    <h3 class="fw-semibold">Temukan Rumah Impian Anda</h3>
    <p>Proyek perumahan berkualitas dengan sistem monitoring pembangunan real-time</p>

    <!-- SEARCH -->
    <div class="search-box mt-4">
        <i class="bi bi-search"></i>
        <input type="text" class="form-control" placeholder="Cari proyek atau lokasi...">
    </div>

    <!-- STATISTICS -->
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center g-4">

            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-1">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-muted">Total Proyek</div>
                        <div class="fw-bold fs-5 text-primary">3</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-2">
                        <i class="bi bi-house-heart"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-muted">Unit Tersedia</div>
                        <div class="fw-bold fs-5 text-success">85</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-3">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-muted">Lokasi</div>
                        <div class="fw-bold fs-5 text-warning">Palembang</div>
                    </div>
                </div>
            </div>

        </div>
</div>

<!-- PROPERTY LIST -->
<div class="container mt-5">
    <h4 class="fw-bold mb-3">Proyek Perumahan</h4>

    <div class="row g-4">

        <!-- CARD 1 -->
        <div class="col-md-4">
            <div class="property-card">
                <div class="position-relative">
                    <span class="status-badge">Sedang Berjalan</span>
                    <img src="{{ asset('images/rumah.jpg') }}">
                </div>
                <div class="p-3">
                    <h5 class="fw-bold">Green Valley Residence</h5>
                    <p class="text-muted"><i class="bi bi-geo-alt"></i> Cikarang, Bekasi</p>
                </div>
            </div>
        </div>

        <!-- CARD 2 -->
        <div class="col-md-4">
            <div class="property-card">
                <div class="position-relative">
                    <span class="status-badge">Sedang Berjalan</span>
                    <img src="{{ asset('images/rumah.jpg') }}">
                </div>
                <div class="p-3">
                    <h5 class="fw-bold">Sunrise Garden</h5>
                    <p class="text-muted"><i class="bi bi-geo-alt"></i> Cibubur, Jakarta Timur</p>
                </div>
            </div>
        </div>

        <!-- CARD 3 -->
        <div class="col-md-4">
            <div class="property-card">
                <div class="position-relative">
                    <span class="status-badge">Sedang Berjalan</span>
                    <img src="{{ asset('images/rumah.jpg') }}">
                </div>
                <div class="p-3">
                    <h5 class="fw-bold">Royal Hills</h5>
                    <p class="text-muted"><i class="bi bi-geo-alt"></i> Depok, Jawa Barat</p>
                </div>
            </div>
        </div>

    </div>
<div class="container mt-5">
    <div class="row">
        <!-- Kolom Lokasi -->
        <div class="col-md-6">
            <h4 class="fw-bold mb-3">Lokasi</h4>
            <iframe 
                title="maps lokasi"
                width="100%" 
                height="350px" 
                id="mapcanvas" 
                src="https://maps.google.com/maps?q=-2.9349269722222227,104.8554916111111&ie=UTF8&iwloc=&output=embed" 
                frameborder="0"
                scrolling="no" 
                marginheight="0" 
                marginwidth="0">
            </iframe>
            <p class="text-muted mt-2">
                <i class="bi bi-geo-alt"></i> Palembang, Sumatra Selatan
            </p>
        </div>

        <!-- Kolom Kantor Pemasaran (TENGAH) -->
        <div class="col-md-6 d-flex align-items-center">
            <div class="text-center w-100">
                <h4 class="fw-bold mb-3">Kantor Pemasaran</h4>
                <p class="text-muted">
                    <strong>PT Kedaton Sejahtera Abadi</strong><br>
                    Perum Green Hayyat Regency no. 18 SUMATERA SELATAN, KOTA PALEMBANG, Sako, Sako<br>
                    Telp : 081310619585<br>
                    Email : ptkedatonsejahteraabadi@gmail.com
                </p>
            </div>
        </div>
    </div>
</div>

    <!-- CTA LOGIN -->
    <div class="cta-section">
        <h5>Tertarik untuk Membooking?</h5>
        <p>Login untuk melihat detail lengkap dan booking unit pilihan Anda</p>

        <a href="{{ route('login') }}" class="btn btn-primary">Login Sekarang</a>
    </div>

</div>

</body>
</html>
