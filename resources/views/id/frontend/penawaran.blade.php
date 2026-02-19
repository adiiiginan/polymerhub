<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minta Penawaran - Jaya Niaga Semesta</title>
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

        .page-header {
            text-align: center;
            margin-bottom: 40px;
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
            max-width: 600px;
            margin: 0 auto;
        }

        /* Quote Form Layout */
        .quote-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        /* Quote Form */
        .quote-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            background: #2563eb;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-help {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Quantity Controls */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            color: #64748b;
            transition: all 0.3s;
        }

        .quantity-btn:hover {
            background: #e2e8f0;
            color: #475569;
        }

        .quantity-input {
            width: 80px;
            text-align: center;
            padding: 8px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-weight: 500;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f9fafb;
            transition: all 0.3s;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .file-upload.dragover {
            border-color: #2563eb;
            background: #dbeafe;
        }

        .file-upload-icon {
            font-size: 32px;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        .file-upload-text {
            color: #6b7280;
            margin-bottom: 5px;
        }

        .file-upload-hint {
            font-size: 12px;
            color: #9ca3af;
        }

        .file-list {
            margin-top: 15px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-remove {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .file-remove:hover {
            background: #fee2e2;
        }

        /* Submit Button */
        .submit-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 25px;
            display: flex;
            gap: 15px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            flex: 1;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
        }

        /* Product Summary Sidebar */
        .product-summary {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .summary-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .product-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-item:last-child {
            margin-bottom: 0;
        }

        .product-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .product-price {
            font-size: 14px;
            font-weight: 600;
            color: #f97316;
        }

        .product-supplier {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
        }

        /* Quote Benefits */
        .benefits-list {
            list-style: none;
        }

        .benefits-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #4b5563;
            font-size: 14px;
        }

        .benefits-list li:before {
            content: "✓";
            background: #10b981;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }

        /* Contact Info */
        .contact-info {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            text-align: center;
        }

        .contact-info h3 {
            margin-bottom: 15px;
        }

        .contact-info p {
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .contact-btn {
            background: white;
            color: #2563eb;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
            margin-top: 15px;
        }

        .contact-btn:hover {
            transform: translateY(-2px);
        }

        /* Success Message */
        .success-message {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .quote-layout {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .submit-section {
                flex-direction: column;
            }

            .page-title {
                font-size: 24px;
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
                <a href="#">Beranda</a> > <a href="#">Produk</a> > <a href="#">Laptop Gaming ROG Strix
                    G15</a> > Minta Penawaran
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Minta Penawaran Harga</h1>
            <p class="page-subtitle">Dapatkan penawaran terbaik dari supplier terpercaya. Isi form di bawah ini dan kami
                akan mengirimkan penawaran dalam 24 jam.</p>
        </div>

        <!-- Success Message -->
        <div class="success-message" id="successMessage">
            <strong>✓ Permintaan penawaran berhasil dikirim!</strong><br>
            Tim kami akan menghubungi Anda dalam 24 jam untuk memberikan penawaran terbaik.
        </div>

        <!-- Quote Layout -->
        <div class="quote-layout">
            <!-- Quote Form -->
            <div class="quote-form">
                <form id="quoteForm">
                    <!-- Company Information -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <span class="section-icon">🏢</span>
                            Informasi Perusahaan
                        </h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="companyName">Nama Perusahaan <span class="required">*</span></label>
                                <input type="text" id="companyName" name="companyName" required>
                            </div>
                            <div class="form-group">
                                <label for="industry">Bidang Usaha</label>
                                <select id="industry" name="industry">
                                    <option value="">Pilih Bidang Usaha</option>
                                    <option value="retail">Retail</option>
                                    <option value="manufacture">Manufaktur</option>
                                    <option value="service">Jasa</option>
                                    <option value="technology">Teknologi</option>
                                    <option value="education">Pendidikan</option>
                                    <option value="healthcare">Kesehatan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="companySize">Ukuran Perusahaan</label>
                                <select id="companySize" name="companySize">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="1-10">1-10 Karyawan</option>
                                    <option value="11-50">11-50 Karyawan</option>
                                    <option value="51-200">51-200 Karyawan</option>
                                    <option value="201-1000">201-1000 Karyawan</option>
                                    <option value="1000+">1000+ Karyawan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="taxId">NPWP/NIB</label>
                                <input type="text" id="taxId" name="taxId" placeholder="Nomor NPWP atau NIB">
                            </div>
                        </div>

                        <div class="form-row full">
                            <div class="form-group">
                                <label for="companyAddress">Alamat Perusahaan <span class="required">*</span></label>
                                <textarea id="companyAddress" name="companyAddress" placeholder="Masukkan alamat lengkap perusahaan" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Person -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <span class="section-icon">👤</span>
                            Kontak Person
                        </h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contactName">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" id="contactName" name="contactName" required>
                            </div>
                            <div class="form-group">
                                <label for="position">Jabatan</label>
                                <input type="text" id="position" name="position" placeholder="Manager Procurement">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Nomor Telepon <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" placeholder="+62 812-3456-7890"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email"
                                    placeholder="nama@perusahaan.com" required>
                            </div>
                        </div>
                    </div>

                    <!-- Product Requirements -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <span class="section-icon">📋</span>
                            Detail Kebutuhan Produk
                        </h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="quantity">Jumlah Unit <span class="required">*</span></label>
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn"
                                        onclick="changeQuantity(-1)">−</button>
                                    <input type="number" id="quantity" name="quantity" value="1"
                                        min="1" class="quantity-input" required>
                                    <button type="button" class="quantity-btn"
                                        onclick="changeQuantity(1)">+</button>
                                </div>
                                <div class="form-help">Minimal pembelian 1 unit</div>
                            </div>
                            <div class="form-group">
                                <label for="deliveryTime">Waktu Pengiriman</label>
                                <select id="deliveryTime" name="deliveryTime">
                                    <option value="">Pilih Waktu</option>
                                    <option value="asap">Segera (1-3 hari)</option>
                                    <option value="1-2weeks">1-2 Minggu</option>
                                    <option value="3-4weeks">3-4 Minggu</option>
                                    <option value="1-2months">1-2 Bulan</option>
                                    <option value="flexible">Fleksibel</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="budget">Budget Estimasi</label>
                                <select id="budget" name="budget">
                                    <option value="">Pilih Range Budget</option>
                                    <option value="10-50">Rp 10 - 50 Juta</option>
                                    <option value="50-100">Rp 50 - 100 Juta</option>
                                    <option value="100-500">Rp 100 - 500 Juta</option>
                                    <option value="500+">Lebih dari Rp 500 Juta</option>
                                    <option value="discuss">Akan Dibahas</option>
                                </select>
                                <div class="form-help">Opsional - membantu kami memberikan penawaran yang sesuai</div>
                            </div>
                            <div class="form-group">
                                <label for="paymentTerms">Terms Pembayaran</label>
                                <select id="paymentTerms" name="paymentTerms">
                                    <option value="">Pilih Terms</option>
                                    <option value="cash">Cash</option>
                                    <option value="30days">NET 30</option>
                                    <option value="60days">NET 60</option>
                                    <option value="installment">Cicilan</option>
                                    <option value="discuss">Akan Dibahas</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row full">
                            <div class="form-group">
                                <label for="specifications">Spesifikasi Khusus</label>
                                <textarea id="specifications" name="specifications"
                                    placeholder="Jelaskan spesifikasi atau kustomisasi khusus yang Anda butuhkan (opsional)"></textarea>
                                <div class="form-help">Misalnya: perlu instalasi software khusus, upgrade RAM, dll.
                                </div>
                            </div>
                        </div>

                        <div class="form-row full">
                            <div class="form-group">
                                <label for="additionalNotes">Catatan Tambahan</label>
                                <textarea id="additionalNotes" name="additionalNotes" placeholder="Informasi tambahan yang ingin Anda sampaikan"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="form-section">
                        <h2 class="section-title">
                            <span class="section-icon">📎</span>
                            Dokumen Pendukung
                        </h2>

                        <div class="form-group">
                            <label>Upload Dokumen (Opsional)</label>
                            <div class="file-upload" id="fileUpload">
                                <div class="file-upload-icon">📄</div>
                                <div class="file-upload-text">Klik atau drag & drop file di sini</div>
                                <div class="file-upload-hint">PDF, DOC, XLS, JPG - Max 5MB per file</div>
                                <input type="file" id="fileInput" multiple
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" style="display: none;">
                            </div>
                            <div class="file-list" id="fileList"></div>
                            <div class="form-help">Upload dokumen seperti RFQ, spesifikasi teknis, atau dokumen
                                perusahaan</div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="submit-section">
                        <button type="submit" class="btn-primary">📧 Kirim Permintaan Penawaran</button>
                        <button type="button" class="btn-secondary" onclick="window.history.back()">←
                            Kembali</button>
                    </div>
                </form>
            </div>

            <!-- Product Summary Sidebar -->
            <div class="product-summary">
                <!-- Selected Product -->
                <div class="summary-card">
                    <h3 class="summary-title">Produk yang Dipilih</h3>
                    <div class="product-item">
                        <div class="product-image">💻</div>
                        <div class="product-details">
                            <div class="product-name">Laptop Gaming ROG Strix G15</div>
                            <div class="product-supplier">PT Teknologi Maju Bersama</div>
                            <div class="product-price">Rp 15.500.000</div>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="summary-card">
                    <h3 class="summary-title">Keuntungan Minta Penawaran</h3>
                    <ul class="benefits-list">
                        <li>Harga khusus untuk pembelian jumlah besar</li>
                        <li>Konsultasi gratis dengan ahli produk</li>
                        <li>Garansi resmi dan after-sales support</li>
                        <li>Terms pembayaran yang fleksibel</li>
                        <li>Pengiriman ke seluruh Indonesia</li>
                        <li>Invoice resmi untuk keperluan pajak</li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="summary-card contact-info">
                    <h3>Butuh Bantuan?</h3>
                    <p>📞 (021) 2345-6789</p>
                    <p>📧 sales@jayaniagas.com</p>
                    <p>💬 Chat WhatsApp: +62 812-3456-7890</p>
                    <button class="contact-btn">Hubungi Kami</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Quantity control
        function changeQuantity(change) {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value) || 1;
            value += change;
            if (value < 1) value = 1;
            input.value = value;
        }

        // File upload handling
        const fileUpload = document.getElementById('fileUpload');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');
        let uploadedFiles = [];

        fileUpload.addEventListener('click', () => fileInput.click());

        fileUpload.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    fileUpload.classList.ad
