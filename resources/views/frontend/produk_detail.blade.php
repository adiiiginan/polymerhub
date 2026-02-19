<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Gaming ROG Strix G15 - Jaya Niaga Semesta</title>
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

        /* Header - Same as original */
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

        /* Product Detail Layout */
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        /* Product Gallery */
        .product-gallery {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .main-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            color: #94a3b8;
            margin-bottom: 20px;
            position: relative;
            cursor: zoom-in;
        }

        .product-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #10b981;
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #94a3b8;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s;
            flex-shrink: 0;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #2563eb;
        }

        /* Product Info */
        .product-info {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .product-category {
            color: #2563eb;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .product-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .product-supplier {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .supplier-badge {
            background: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .product-price {
            font-size: 32px;
            font-weight: bold;
            color: #f97316;
            margin-bottom: 20px;
        }

        .price-note {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .stars {
            color: #fbbf24;
            font-size: 18px;
        }

        .rating-text {
            color: #64748b;
            font-size: 14px;
        }

        /* Action Buttons */
        .product-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .action-row {
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            flex: 1;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #f97316;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            flex: 1;
            font-size: 16px;
        }

        .btn-secondary:hover {
            background: #ea580c;
        }

        .btn-outline {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            font-size: 16px;
        }

        .btn-outline:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        /* Product Specs */
        .product-specs {
            border-top: 1px solid #e2e8f0;
            padding-top: 25px;
        }

        .specs-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .spec-label {
            color: #64748b;
            font-weight: 500;
        }

        .spec-value {
            color: #1e293b;
            font-weight: 500;
            text-align: right;
        }

        /* Tabs Section */
        .tabs-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
        }

        .tabs-nav {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 20px 30px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-btn:hover,
        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-content {
            padding: 30px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Description */
        .description {
            line-height: 1.6;
            color: #4b5563;
        }

        .description h3 {
            color: #1e293b;
            margin: 25px 0 15px 0;
            font-size: 18px;
        }

        .description ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .description li {
            margin-bottom: 8px;
        }

        /* Reviews */
        .review-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 0;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .reviewer-name {
            font-weight: 600;
            color: #1e293b;
        }

        .review-date {
            color: #64748b;
            font-size: 14px;
        }

        .review-rating {
            color: #fbbf24;
            margin-bottom: 10px;
        }

        .review-text {
            color: #4b5563;
            line-height: 1.5;
        }

        /* Related Products */
        .related-section {
            margin-top: 50px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .related-products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .related-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #94a3b8;
        }

        .related-info {
            padding: 20px;
        }

        .related-name {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .related-price {
            font-size: 18px;
            font-weight: bold;
            color: #f97316;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .product-detail {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .action-row {
                flex-direction: column;
            }

            .tabs-nav {
                flex-wrap: wrap;
            }

            .tab-btn {
                padding: 15px 20px;
                font-size: 14px;
            }

            .related-products {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
                <a href="#" style="color: #2563eb;">Produk</a>
                <a href="#">Kategori</a>
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
            <nav class="breadcrumb-nav">
                <a href="#">Beranda</a> > <a href="#">Produk</a> > <a href="#">Elektronik</a> > Laptop
                Gaming ROG Strix G15
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Product Detail -->
        <div class="product-detail">
            <!-- Product Gallery -->
            <div class="product-gallery">
                <div class="main-image" id="mainImage">
                    💻
                    <span class="product-badge">Terlaris</span>
                </div>
                <div class="thumbnail-gallery">
                    <div class="thumbnail active">💻</div>
                    <div class="thumbnail">⌨️</div>
                    <div class="thumbnail">🖱️</div>
                    <div class="thumbnail">📱</div>
                    <div class="thumbnail">🔌</div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info">
                <div class="product-category">Elektronik</div>
                <h1 class="product-name">Laptop Gaming ROG Strix G15</h1>
                <div class="product-supplier">
                    PT Teknologi Maju Bersama
                    <span class="supplier-badge">Verified</span>
                </div>

                <div class="product-rating">
                    <div class="stars">★★★★★</div>
                    <span class="rating-text">4.8 (127 ulasan)</span>
                </div>

                <div class="product-price">Rp 15.500.000</div>
                <div class="price-note">*Harga belum termasuk PPN dan ongkos kirim</div>

                <div class="product-actions">
                    <div class="action-row">
                        <button class="btn-primary">💬 Hubungi Supplier</button>
                        <a href="{{ route('frontend.penawaran') }}"><button class="btn-secondary">📋 Minta
                                Penawaran</button></a>
                    </div>
                    <div class="action-row">
                        <button class="btn-outline">❤️ Simpan Produk</button>
                        <button class="btn-outline">📤 Bagikan</button>
                    </div>
                </div>

                <div class="product-specs">
                    <h3 class="specs-title">Spesifikasi Produk</h3>
                    <div class="spec-item">
                        <span class="spec-label">Prosesor</span>
                        <span class="spec-value">Intel Core i7-12700H</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">RAM</span>
                        <span class="spec-value">16GB DDR5</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Storage</span>
                        <span class="spec-value">1TB NVMe SSD</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">GPU</span>
                        <span class="spec-value">NVIDIA RTX 4060 8GB</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Display</span>
                        <span class="spec-value">15.6" FHD 144Hz</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Garansi</span>
                        <span class="spec-value">2 Tahun Resmi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="tabs-section">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="description">Deskripsi</button>
                <button class="tab-btn" data-tab="specifications">Spesifikasi Lengkap</button>
                <button class="tab-btn" data-tab="reviews">Ulasan (127)</button>
                <button class="tab-btn" data-tab="supplier">Info Supplier</button>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="description">
                    <div class="description">
                        <p>ASUS ROG Strix G15 adalah laptop gaming yang dirancang untuk memberikan performa maksimal
                            dalam bermain game dan aktivitas komputasi berat lainnya. Dengan desain yang agresif dan
                            fitur-fitur canggih, laptop ini menjadi pilihan ideal untuk para gamer profesional dan
                            enthusiast.</p>

                        <h3>Performa Unggulan</h3>
                        <p>Ditenagai oleh prosesor Intel Core i7-12700H generasi ke-12 dengan arsitektur hybrid yang
                            menggabungkan Performance-cores dan Efficiency-cores untuk mengoptimalkan performa dan
                            efisiensi daya. GPU NVIDIA GeForce RTX 4060 dengan 8GB VRAM memastikan gameplay yang smooth
                            pada setting tinggi.</p>

                        <h3>Fitur Utama:</h3>
                        <ul>
                            <li>Prosesor Intel Core i7-12700H (2.3GHz hingga 4.7GHz)</li>
                            <li>RAM 16GB DDR5-4800 (upgradeable hingga 32GB)</li>
                            <li>Storage 1TB PCIe 4.0 NVMe SSD</li>
                            <li>Layar 15.6" FHD (1920x1080) dengan refresh rate 144Hz</li>
                            <li>Teknologi NVIDIA DLSS 3.0 dan Ray Tracing</li>
                            <li>Sistem pendinginan ROG Intelligent Cooling</li>
                            <li>Keyboard RGB Backlit dengan WASD highlighted</li>
                            <li>Audio DTS:X Ultra untuk pengalaman suara immersive</li>
                        </ul>

                        <h3>Konektivitas Lengkap</h3>
                        <p>Dilengkapi dengan berbagai port termasuk USB 3.2 Type-A, USB-C, HDMI 2.1, Ethernet RJ45, dan
                            audio jack 3.5mm. WiFi 6E dan Bluetooth 5.2 memastikan konektivitas wireless yang stabil dan
                            cepat.</p>
                    </div>
                </div>

                <div class="tab-pane" id="specifications">
                    <div class="description">
                        <h3>Spesifikasi Teknis Lengkap</h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Prosesor</td>
                                <td style="padding: 10px 0;">Intel Core i7-12700H (14 cores, 20 threads)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Chipset</td>
                                <td style="padding: 10px 0;">Intel HM670</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Memori</td>
                                <td style="padding: 10px 0;">16GB DDR5-4800 SO-DIMM (2 slot, max 32GB)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Storage</td>
                                <td style="padding: 10px 0;">1TB PCIe 4.0 NVMe M.2 SSD (2 slot M.2)</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Display</td>
                                <td style="padding: 10px 0;">15.6" FHD (1920x1080) IPS, 144Hz, 3ms, 100% sRGB</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Graphics</td>
                                <td style="padding: 10px 0;">NVIDIA GeForce RTX 4060 8GB GDDR6</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Audio</td>
                                <td style="padding: 10px 0;">Smart Amplifier Technology, DTS:X Ultra</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Keyboard</td>
                                <td style="padding: 10px 0;">Backlit Chiclet dengan WASD highlighted</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Wireless</td>
                                <td style="padding: 10px 0;">WiFi 6E (802.11ax), Bluetooth 5.2</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Battery</td>
                                <td style="padding: 10px 0;">90WHr, 4-Cell Li-ion</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 10px 0; font-weight: 600;">Dimensi</td>
                                <td style="padding: 10px 0;">35.4 x 25.9 x 2.7 cm</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; font-weight: 600;">Berat</td>
                                <td style="padding: 10px 0;">2.3 kg</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="reviews">
                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Ahmad Rizki</span>
                            <span class="review-date">15 Januari 2025</span>
                        </div>
                        <div class="review-rating">★★★★★</div>
                        <div class="review-text">Laptop gaming terbaik yang pernah saya beli! Performa sangat memuaskan
                            untuk bermain game AAA dengan setting ultra. Pendinginan juga sangat baik, tidak overheat
                            meski digunakan berjam-jam. Layar 144Hz memberikan pengalaman gaming yang sangat smooth.
                        </div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Sari Indah</span>
                            <span class="review-date">8 Januari 2025</span>
                        </div>
                        <div class="review-rating">★★★★★</div>
                        <div class="review-text">Sebagai content creator, laptop ini sangat membantu untuk rendering
                            video dan editing. RAM 16GB dan SSD 1TB memberikan performa yang sangat cepat. Build quality
                            juga sangat solid dan premium.</div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name">Budi Santoso</span>
                            <span class="review-date">2 Januari 2025</span>
                        </div>
                        <div class="review-rating">★★★★☆</div>
                        <div class="review-text">Overall bagus, performa gaming excellent. Hanya saja baterai agak
                            cepat habis saat digunakan untuk gaming intensif. Tapi itu wajar untuk laptop gaming sekelas
                            ini. Recommended untuk yang serius gaming!</div>
                    </div>
                </div>

                <div class="tab-pane" id="supplier">
                    <div class="description">
                        <h3>PT Teknologi Maju Bersama</h3>
                        <p><strong>Status:</strong> Supplier Terverifikasi ✅</p>
                        <p><strong>Tahun Berdiri:</strong> 2015</p>
                        <p><strong>Lokasi:</strong> Jakarta Selatan, DKI Jakarta</p>
                        <p><strong>Spesialisasi:</strong> Perangkat Teknologi, Laptop Gaming, Komputer</p>

                        <h3>Tentang Supplier</h3>
                        <p>PT Teknologi Maju Bersama adalah distributor resmi produk teknologi terkemuka di Indonesia
                            dengan fokus pada laptop gaming dan komputer high-performance. Kami telah melayani ribuan
                            pelanggan korporat dan individual selama lebih dari 8 tahun
