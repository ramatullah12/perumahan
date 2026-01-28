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
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --soft-bg: #f8fafc;
            --dark-text: #0f172a;
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
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            padding: 18px 0;
            box-shadow: 0 4px 30px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        /* PREMIUM HERO SECTION */
        .hero-header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 120px 0 180px;
            border-bottom-left-radius: 80px;
            border-bottom-right-radius: 80px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-header::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
            opacity: 0.1;
        }

        .hero-header h1 {
            font-weight: 800;
            font-size: clamp(2.5rem, 6vw, 3.8rem);
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -2px;
        }

        .search-box {
            max-width: 800px;
            margin: auto;
            position: relative;
            z-index: 5;
        }

        .search-box input {
            height: 75px;
            border-radius: 25px;
            padding-left: 65px;
            border: none;
            font-size: 1.1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            transform: scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }

        .search-box i {
            position: absolute;
            top: 25px;
            left: 25px;
            color: var(--primary-color);
            font-size: 24px;
        }

        /* STATS FLOATING CARDS */
        .stats-container {
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            display: flex;
            gap: 20px;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stats-card:hover { 
            transform: translateY(-12px); 
            background: white;
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .stats-icon {
            font-size: 32px;
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
        }

        .stats-1 { background: #dbeafe; color: #1e40af; }
        .stats-2 { background: #dcfce7; color: #15803d; }
        .stats-3 { background: #fef3c7; color: #b45309; }

        /* PROPERTY CARDS */
        .property-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #f1f5f9;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
        }

        .property-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-color);
        }

        .img-wrapper {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .property-card img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .property-card:hover img { transform: scale(1.15); }

        .status-badge {
            background: rgba(37, 99, 235, 0.9);
            backdrop-filter: blur(8px);
            padding: 10px 22px;
            border-radius: 50px;
            color: white;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: absolute;
            top: 25px;
            left: 25px;
            z-index: 2;
        }

        .price-badge {
            position: absolute;
            bottom: 20px;
            right: 25px;
            background: white;
            padding: 8px 18px;
            border-radius: 15px;
            font-weight: 800;
            color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .cta-section {
            background: linear-gradient(rgba(37, 99, 235, 0.95), rgba(30, 64, 175, 0.95)), 
                        url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 40px;
            text-align: center;
            border-radius: 60px;
            margin-top: 120px;
            color: white;
        }

        .btn-premium {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 18px 45px;
            border-radius: 20px;
            font-weight: 800;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            transform: scale(1.05);
            background: #f8fafc;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                <img src="{{ asset('images/logo.jpg') }}" height="45" class="rounded-circle shadow-sm">
                <span class="fw-bold text-primary tracking-tighter" style="letter-spacing: -1px; font-size: 1.4rem;">KEDATON SEJAHTERA</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('login') }}" class="btn btn-link text-decoration-none fw-bold text-muted px-3 d-none d-sm-block">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-5 py-2.5 fw-bold shadow-lg">Gabung Sekarang</a>
            </div>
        </div>
    </nav>

    <div class="hero-header text-center">
        <div class="container">
            <span class="badge bg-white bg-opacity-20 rounded-pill px-4 py-2 mb-4 fw-bold">🏠 Developer Terpercaya Sejak 2010</span>
            <h1>Hunian Eksklusif Untuk<br>Gaya Hidup Modern</h1>
            <p class="mx-auto mb-5 opacity-75" style="max-width: 600px; font-size: 1.2rem;">Menghadirkan kenyamanan dan nilai investasi terbaik di lokasi strategis Palembang & Sumatera Selatan.</p>

            <form action="#" method="GET" class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="query" class="form-control" placeholder="Cari nama perumahan, tipe, atau lokasi...">
            </form>
        </div>
    </div>

    <div class="container stats-container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-1 d-none d-sm-flex"><i class="bi bi-buildings-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Total Proyek</div>
                        <div class="fw-bold fs-3 text-primary">{{ $totalProyek }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-2 d-none d-sm-flex"><i class="bi bi-house-heart-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Unit Tersedia</div>
                        <div class="fw-bold fs-3 text-success">{{ $unitTersedia }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stats-card">
                    <div class="stats-icon stats-3 d-none d-sm-flex"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div class="fw-semibold text-muted small text-uppercase">Lokasi Utama</div>
                        <div class="fw-bold fs-5 text-warning">Palembang & Sekitarnya</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-black mb-2" style="font-weight: 800; font-size: 2.5rem; letter-spacing: -1.5px;">Proyek Terbaru</h2>
                <p class="text-muted mb-0">Eksplorasi hunian impian dengan desain arsitektur kontemporer.</p>
            </div>
            <a href="#" class="btn btn-outline-primary rounded-pill px-4 fw-bold d-none d-md-block">Lihat Semua Proyek</a>
        </div>

        <div class="row g-4">
            @forelse($projects as $project)
            <div class="col-md-4">
                <div class="property-card">
                    <div class="img-wrapper">
                        <span class="status-badge">Signature Collection</span>
                        <div class="price-badge">Start from Rp 400jt-an</div>
                        <img src="{{ asset('storage/' . $project->image) }}" 
                             alt="{{ $project->nama_proyek }}"
                             onerror="this.src='{{ asset('images/rumah.jpg') }}'">
                    </div>
                    <div class="card-content p-4">
                        <h4 class="fw-bold mb-2 text-truncate" title="{{ $project->nama_proyek }}">{{ $project->nama_proyek }}</h4>
                        <div class="d-flex align-items-center text-muted small mb-4">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                            <span class="text-truncate fw-medium">{{ $project->lokasi }}</span>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3 text-center">
                                    <small class="text-muted d-block">Terjual</small>
                                    <span class="fw-bold">{{ $project->terjual ?? 0 }} Unit</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3 text-center">
                                    <small class="text-muted d-block">Sisa</small>
                                    <span class="fw-bold text-success">{{ $project->tersedia ?? 0 }} Unit</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('proyek.detail', $project->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold py-3 shadow-sm">
                            Eksplorasi Unit <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-5 shadow-sm border border-dashed">
                    <i class="bi bi-house-slash fs-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fw-bold mb-0">Segera hadir proyek eksklusif untuk Anda.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="container mt-5 pt-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-4" style="font-size: 2rem; letter-spacing: -1px;">Kunjungi Kantor Kami</h3>
                <div class="rounded-5 overflow-hidden shadow-lg border-0" style="height: 450px;">
                    <iframe width="100%" height="100%" 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.4447385437!2d104.7733!3d-2.936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMsKwNTYnMDUuNiJTIDEwNMKwNDYnMjMuOSJF!5e0!3m2!1sen!2sid!4v1700000000000" 
                        style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-5 office-card bg-white shadow-sm rounded-5 border">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3">Headquarter</span>
                    <h4 class="fw-bold mb-4">Kantor Pemasaran Pusat</h4>
                    <div class="mb-4 pb-4 border-bottom">
                        <h5 class="fw-bold text-primary mb-2">PT Kedaton Sejahtera Abadi</h5>
                        <p class="text-muted mb-0 lh-lg font-medium">Perum Green Hayyat Regency No. 18, Sako, Palembang, Sumatera Selatan.</p>
                    </div>
                    <div class="vstack gap-4">
                        <div class="d-flex align-items-center gap-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-whatsapp text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase">Konsultasi WA</small>
                                <span class="fw-bold fs-5">0813-1061-9585</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-clock-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold text-uppercase">Jam Operasional</small>
                                <span class="fw-bold">Senin - Sabtu: 09:00 - 17:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="cta-section">
            <h2 class="fw-bold mb-4" style="font-size: 2.8rem; letter-spacing: -2px;">Wujudkan Rumah Impian Anda</h2>
            <p class="mb-5 opacity-90 mx-auto" style="max-width: 650px; font-size: 1.1rem;">Jangan tunda kebahagiaan keluarga Anda. Dapatkan penawaran bunga KPR rendah dan promo bebas biaya surat-surat khusus bulan ini.</p>
            <a href="{{ route('register') }}" class="btn btn-premium shadow-lg">Ambil Promo Sekarang <i class="bi bi-chevron-right ms-2"></i></a>
        </div>
    </div>

    <footer class="py-5 mt-5 border-top bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted small mb-0 fw-bold">
                        © {{ date('Y') }} PT Kedaton Sejahtera Abadi. <span class="text-primary">Membangun Dengan Hati.</span>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="#" class="text-muted"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-youtube fs-5"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>