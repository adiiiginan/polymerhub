<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - Jaya Niaga Semesta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav a {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #2563eb;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-btn,
        .cart-btn {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .search-btn:hover,
        .cart-btn:hover {
            background-color: #f1f5f9;
        }

        .cart-badge {
            background: #f97316;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .login-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background: #1d4ed8;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .breadcrumb-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb-nav {
            color: #64748b;
            font-size: 14px;
        }

        .breadcrumb-nav a {
            color: #2563eb;
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 18px;
            margin-bottom: 40px;
        }

        /* Statistics Cards */
        .stats-section {
            margin-bottom: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        /* Category Grid */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .category-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .category-header {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .category-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(29, 78, 216, 0.05));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .category-card:hover .category-header::before {
            opacity: 1;
        }

        .category-icon {
            font-size: 64px;
            z-index: 2;
            position: relative;
            transition: transform 0.3s;
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #10b981;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            z-index: 3;
        }

        .category-info {
            padding: 25px;
        }

        .category-name {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .category-description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .category-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .category-count {
            background: #f1f5f9;
            color: #2563eb;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .category-suppliers {
            color: #64748b;
            font-size: 12px;
        }

        .category-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .view-products-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .view-products-btn:hover {
            background: #1d4ed8;
            transform: translateX(2px);
        }

        .trending-indicator {
            color: #f97316;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Featured Categories Section */
        .featured-section {
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .featured-card {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 30px;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .featured-card:hover {
            transform: scale(1.02);
        }

        .featured-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .featured-icon {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .featured-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .featured-count {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Search Section */
        .search-section {
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }

        .search-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .search-subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
            gap: 15px;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .search-btn-main {
            background: #f97316;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn-main:hover {
            background: #ea580c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .page-title {
                font-size: 24px;
            }

            .categories-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-form {
                flex-direction: column;
            }

            .featured-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <div class="logo-icon">JN</div>
                JAYA NIAGA SEMESTA
            </div>

            <nav class="nav">
                <a href="#">Beranda</a>
                <a href="#">Produk</a>
                <a href="#" style="color: #2563eb;">Kategori</a>
                <a href="#">Supplier</a>
                <a href="#">Tentang Kami</a>
            </nav>

            <div class="header-actions">
                <button class="search-btn">🔍</button>
                <button class="cart-btn" style="position: relative;">
                    🛒
                    <span class="cart-badge">3</span>
                </button>
                <button class="login-btn">Login Perusahaan</button>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="breadcrumb-container">
            <div class="breadcrumb-nav">
                <a href="#">Beranda</a> / <span>Kategori</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <h1 class="page-title">Kategori Produk</h1>
        <p class="page-subtitle">Jelajahi berbagai kategori produk dari ribuan supplier terpercaya</p>

        <!-- Statistics -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number">12</div>
                    <div class="stat-label">Kategori Utama</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-number">1,247</div>
                    <div class="stat-label">Supplier Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🛍️</div>
                    <div class="stat-number">15,683</div>
                    <div class="stat-label">Total Produk</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🌟</div>
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Rating Rata-rata</div>
                </div>
            </div>
        </section>

        <!-- Search Section -->
        <section class="search-section">
            <h2 class="search-title">Cari Kategori Spesifik</h2>
            <p class="search-subtitle">Temukan kategori yang Anda butuhkan dengan mudah</p>
            <div class="search-form">
                <input type="text" class="search-input" placeholder="Masukkan nama kategori...">
                <button class="search-btn-main">Cari Kategori</button>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="featured-section">
            <h2 class="section-title">
                🔥 Kategori Terpopuler
            </h2>
            <div class="featured-grid">
                <div class="featured-card">
                    <div class="featured-icon">💻</div>
                    <div class="featured-name">Elektronik</div>
                    <div class="featured-count">2,145 produk tersedia</div>
                </div>
                <div class="featured-card">
                    <div class="featured-icon">🏢</div>
                    <div class="featured-name">Peralatan Kantor</div>
                    <div class="featured-count">1,832 produk tersedia</div>
                </div>
                <div class="featured-card">
                    <div class="featured-icon">☕</div>
                    <div class="featured-name">Makanan & Minuman</div>
                    <div class="featured-count">1,657 produk tersedia</div>
                </div>
            </div>
        </section>

        <!-- All Categories Grid -->
        <section>
            <h2 class="section-title">Semua Kategori</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">💻</div>
                        <span class="category-badge">Terlaris</span>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Elektronik & Teknologi</h3>
                        <p class="category-description">Laptop, smartphone, komputer, perangkat elektronik, dan aksesori
                            teknologi terbaru</p>
                        <div class="category-stats">
                            <span class="category-count">2,145 produk</span>
                            <span class="category-suppliers">154 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">📈 Trending</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">🏢</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Peralatan Kantor</h3>
                        <p class="category-description">Furniture kantor, alat tulis, mesin fotocopy, printer, dan
                            perlengkapan administrasi</p>
                        <div class="category-stats">
                            <span class="category-count">1,832 produk</span>
                            <span class="category-suppliers">89 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">📈 Naik</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">☕</div>
                        <span class="category-badge">Organik</span>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Makanan & Minuman</h3>
                        <p class="category-description">Produk makanan segar, minuman, snack, bahan makanan, dan produk
                            organik berkualitas</p>
                        <div class="category-stats">
                            <span class="category-count">1,657 produk</span>
                            <span class="category-suppliers">203 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">🔥 Hot</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">👕</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Tekstil & Fashion</h3>
                        <p class="category-description">Pakaian, kain, batik, seragam, dan produk fashion dari desainer
                            lokal terbaik</p>
                        <div class="category-stats">
                            <span class="category-count">1,429 produk</span>
                            <span class="category-suppliers">167 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">💎 Premium</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">🧱</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Bahan Bangunan</h3>
                        <p class="category-description">Semen, besi, cat, genteng, pipa, dan material konstruksi
                            berkualitas tinggi</p>
                        <div class="category-stats">
                            <span class="category-count">987 produk</span>
                            <span class="category-suppliers">76 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">🏗️ Stabil</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">🔧</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Alat & Mesin Industri</h3>
                        <p class="category-description">Peralatan industri, mesin produksi, alat pertukangan, dan
                            equipment manufacturing</p>
                        <div class="category-stats">
                            <span class="category-count">756 produk</span>
                            <span class="category-suppliers">45 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">⚡ Spesialis</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">🌿</div>
                        <span class="category-badge">Eco-Friendly</span>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Pertanian & Perkebunan</h3>
                        <p class="category-description">Bibit tanaman, pupuk organik, alat pertanian, dan produk hasil
                            bumi berkualitas</p>
                        <div class="category-stats">
                            <span class="category-count">643 produk</span>
                            <span class="category-suppliers">112 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">🌱 Natural</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">💊</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Kesehatan & Kecantikan</h3>
                        <p class="category-description">Produk kesehatan, suplemen, kosmetik, dan peralatan medis dari
                            brand terpercaya</p>
                        <div class="category-stats">
                            <span class="category-count">892 produk</span>
                            <span class="category-suppliers">128 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">💚 Wellness</span>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">🚗</div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Otomotif & Transportasi</h3>
                        <p class="category-description">Suku cadang kendaraan, oli, ban, aksesoris mobil dan motor dari
                            supplier resmi</p>
                        <div class="category-stats">
                            <span class="category-count">1,234 produk</span>
                            <span class="category-suppliers">98 supplier</span>
                        </div>
                        <div class="category-action">
                            <button class="view-products-btn">Lihat Produk →</button>
                            <span class="trending-indicator">🔧 Service</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Category card interactions
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('button')) {
                    const categoryName = this.querySelector('.category-name').textContent;
                    console.log('Category clicked:', categoryName);
                    // In a real application, this would navigate to the category products page
                }
            });
        });

        // View products button functionality
        document.querySelectorAll('.view-products-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const categoryName = this.closest('.category-card').querySelector('.category-name')
                    .textContent;
                console.log('View products for:', categoryName);
                // In a real application, this would navigate to filtered products page
            });
        });

        // Search functionality
        document.querySelector('.search-btn-main').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-input').value;
            console.log('Searching for category:', searchTerm);
            // In a real application, this would filter categories
        });

        // Search input enter key
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-btn-main').click();
            }
        });

        // Header search and cart functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            console.log('Header search clicked');
        });

        document.querySelector('.cart-btn').addEventListener('click', function() {
            console.log('Cart opened');
        });

        // Add hover effects for featured cards
        document.querySelectorAll('.featured-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1.02)';
            });
        });
    </script>
</body>

</html>
