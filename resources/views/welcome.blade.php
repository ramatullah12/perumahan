<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kedaton Sejahtera Abadi | Properti Masa Depan Anda</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --soft-bg: #f8fafc;
            --dark-text: #1e293b;
            --accent-green: #10b981;
        }

        body {
            background: var(--soft-bg);
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--dark-text);
            overflow-x: hidden;
        }

        /* MODERN NAVBAR */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* PREMIUM HERO SECTION */
        .hero-header {
            background: linear-gradient(135deg, #0d6efd 0%, #004dc7 100%);
            padding: 100px 0 160px;
            border-bottom-left-radius: 60px;
            border-bottom-right-radius: 60px;
            color: white;
            position: relative;
        }

        .hero-header h1 {
            font-weight: 800;
            font-size: clamp(2.2rem, 5vw, 3.2rem);
            margin-bottom: 15px;
            letter-spacing: -1.5px;
        }

        .search-box {
            max-width: 750px;
            margin: auto;
            position: relative;
            transform: translateY(10px);
        }

        .search-box input {
            height: 65px;
            border-radius: 20px;
            padding-left: 60px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .search-box i {
            position: absolute;
            top: 20px;
            left: 22px;
            color: var(--primary-color);
            font-size: 22px;
        }

        /* STATS CARDS */
        .stats-container {
            margin-top: -65px;
            position: relative;
            z-index: 10;
        }

        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .stats-card:hover { transform: translateY(-5px); }

        .stats-icon {
            font-size: 30px;
            padding: 15px;
            border-radius: 18px;
            line-height: 1;
        }

        .stats-1 { background: #eff6ff; color: #2563eb; }
        .stats-2 { background: #f0fdf4; color: #16a34a; }
        .stats-3 { background: #fff7ed; color: #ea580c; }

        /* PROPERTY CARDS - FIXING EMPTY SPACE */
        .property-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .img-wrapper {
            position: relative;
            overflow: hidden;
            height: 240px; /* Fixed height for image area */
            background-color: #e2e8f0;
        }

        .property-card img {
            height: 100%;
            width: 100%;
            object-fit: cover; /* Ensures image fills the area without distortion */
            transition: transform 0.6s ease;
        }

        .status-badge {
            background: rgba(16, 185, 129, 0.9);
            backdrop-filter: blur(4px);
            padding: 8px 18px;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 11px;
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 2;
            text-uppercase: uppercase;
        }

        .card-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* CTA & FOOTER */
        .cta-section {
            background: linear-gradient(rgba(13, 110, 253, 0.08), rgba(13, 110, 253, 0.08)), 
                        url('https://www.transparenttextures.com/patterns/cubes.png');
            padding: 80px 40px;
            text-align: center;
            border-radius: 40px;
            margin-top: 100px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('images/logo.jpg') }}" height="40" class="rounded-circle shadow-sm">
                <span class="fw-bold text-primary" style="letter-spacing: -0.5px;">KEDATON SEJAHTERA</span>
            </a>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-link text-decoration-none fw-bold text-muted px-4">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Daftar</a>
            </div>
        </div>
    </nav>

    <div class="hero-header text-center">
        <div class="container">
            <h1>Hunian Impian<br>Keluarga Masa Kini</h1>
            <p class="mx-auto" style="max-width: 650px; opacity: 0.9; font-size: 1.1rem;">Menghadirkan kenyamanan dan nilai investasi terbaik di lokasi strategis seluruh Indonesia.</p>

            <form action="#" method="GET" class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="query" class="form-control" placeholder="Cari nama proyek atau wilayah...">
            </form>
        </div>
    </div>

    <div class="container stats-container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4">
                <div class="stats-card h-100">
                    <div class="stats-icon stats-1 d-none d-sm-block"><i class="bi bi-buildings-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Total Proyek</div>
                        <div class="fw-bold fs-4 text-primary">{{ $totalProyek ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stats-card h-100">
                    <div class="stats-icon stats-2 d-none d-sm-block"><i class="bi bi-house-check-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Unit Tersedia</div>
                        <div class="fw-bold fs-4 text-success">{{ $unitTersedia ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stats-card h-100">
                    <div class="stats-icon stats-3 d-none d-sm-block"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Lokasi Utama</div>
                        <div class="fw-bold fs-4 text-warning">Sumatera Selatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pt-5">
        <div class="mb-5">
            <h2 class="fw-bold mb-2">Proyek Perumahan Terbaru</h2>
            <p class="text-muted">Pilihan hunian eksklusif yang dirancang khusus untuk keluarga Anda.</p>
        </div>

        <div class="row g-4">
            @forelse($projects as $project)
            <div class="col-md-4">
                <div class="property-card">
                    <div class="img-wrapper">
                        <span class="status-badge">Sedang Berjalan</span>
                        
                        @php
                            $imgUrl = $project->image ?? $project->gambar;
                        @endphp

                        @if($imgUrl)
                            @if(Str::startsWith($imgUrl, ['http://', 'https://']))
                                {{-- Jika URL Eksternal (Cloudinary) --}}
                                <img src="{{ $imgUrl }}" alt="{{ $project->nama_proyek }}">
                            @else
                                {{-- Jika URL Lokal --}}
                                <img src="{{ asset('storage/' . $imgUrl) }}" 
                                     alt="{{ $project->nama_proyek }}"
                                     onerror="this.src='https://images.unsplash.com/photo-1580587771525-78b9dba3b914?q=80&w=800'">
                            @endif
                        @else
                            {{-- Placeholder jika tidak ada data --}}
                            <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?q=80&w=800" alt="Rumah Impian">
                        @endif
                    </div>

                    <div class="card-content">
                        <div class="mb-4">
                            <h5 class="fw-bold text-truncate" title="{{ $project->nama_proyek }}">{{ $project->nama_proyek }}</h5>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-geo-alt me-2 text-primary"></i>
                                <span class="text-truncate">{{ $project->lokasi }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('proyek.detail', $project->id) }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2 shadow-sm">
                            Lihat Detail Proyek
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-5 shadow-sm border border-dashed">
                    <i class="bi bi-house-slash fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fw-bold">Saat ini belum ada proyek perumahan yang tersedia.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- MAPS & CONTACT --}}
    <div class="container mt-5 pt-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-4">Lokasi Kami</h3>
                <div class="rounded-5 overflow-hidden shadow-lg border-0" style="height: 400px; background: #eee;">
                    <iframe width="100%" height="100%" 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127504.42571212853!2d104.7001438258327!3d-2.9547942154546524!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b75e8cd77227d%3A0x3039d80b220d040!2sPalembang%2C%20South%20Sumatra!5e0!3m2!1sen!2sid!4v1700000000000" 
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 p-lg-5 bg-white shadow-sm rounded-5 border">
                    <h4 class="fw-bold mb-4">Kantor Pemasaran</h4>
                    <div class="mb-4 pb-3 border-bottom">
                        <h5 class="fw-bold text-primary mb-2">PT Kedaton Sejahtera Abadi</h5>
                        <p class="text-muted mb-0">Perum Green Hayyat Regency No. 18, Sako, Palembang, Sumatera Selatan.</p>
                    </div>
                    <div class="vstack gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <span class="fw-bold">0813-1061-9585</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                                <i class="bi bi-envelope-at-fill"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">E-mail</small>
                                <span class="fw-bold">ptkedaton@gmail.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="cta-section">
            <h2 class="fw-bold mb-3">Siap Memiliki Rumah Sendiri?</h2>
            <p class="text-muted mb-5 px-md-5">Wujudkan impian keluarga Anda bersama hunian berkualitas kami. Proses mudah, aman, dan terpercaya.</p>
            <a href="{{ route('register') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg">Mulai Booking Sekarang</a>
        </div>
    </div>

    <footer class="py-5 mt-5 border-top bg-white">
        <div class="container text-center">
            <p class="text-muted small mb-0 fw-medium">
                © {{ date('Y') }} PT Kedaton Sejahtera Abadi. Seluruh Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>