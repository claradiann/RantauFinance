<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rantau Finance - Platform manajemen keuangan bulanan yang cerdas dan modern. Kelola pemasukan, pengeluaran, dan budget dengan mudah.">
    <title>Rantau Finance — Kelola Keuangan Cerdas</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar" id="navbar">
    <a href="/" class="nav-logo"><img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance</a>
    <ul class="nav-links">
        <li><a href="#features">Fitur</a></li>
        <li><a href="#how-it-works">Cara Kerja</a></li>
        <li><a href="#pricing">Harga</a></li>
        <li><a href="#reviews">Ulasan</a></li>
        <li><a href="#faq">FAQ</a></li>
    </ul>
    <div class="nav-cta">
        <a href="/login" class="btn-outline-custom">Masuk</a>
        <a href="/register" class="btn-primary-custom">Daftar</a>
    </div>
</nav>

{{-- ===== HERO ===== --}}
<section class="hero" id="hero">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge">
                <span>🚀</span> Platform Keuangan #1 untuk Rantau
            </div>
            <h1>
                Kelola Keuangan<br>
                <span class="gradient-text">Lebih Cerdas</span><br>
                & Terkontrol
            </h1>
            <p>
                Pantau pemasukan, kendalikan pengeluaran, dan raih tujuan finansialmu
                dengan platform manajemen keuangan yang intuitif dan powerful.
            </p>
            <div class="hero-buttons">
                <a href="/register" class="btn-primary-custom btn-lg">
                    Mulai Gratis
                </a>
                <a href="#features" class="btn-outline-custom btn-lg">
                    Pelajari Lebih
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <h3>10K+</h3>
                    <p>Pengguna Aktif</p>
                </div>
                <div class="stat">
                    <h3>Rp 2M+</h3>
                    <p>Dana Terkelola</p>
                </div>
                <div class="stat">
                    <h3>4.9★</h3>
                    <p>Rating Pengguna</p>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="floating-badge top-right" style="animation-delay: 0.5s;">
                <span class="icon">📈</span> +Rp 2.500.000
            </div>
            <div class="floating-badge bottom-left" style="animation-delay: 1s;">
                <span class="icon">🛡️</span> 100% Aman
            </div>
            <div class="hero-card">
                <div class="hero-card-header">
                    <h4>Dashboard Keuangan</h4>
                    <span class="badge-live">● Live</span>
                </div>
                <div class="card-balance">
                    <p>Saldo Total</p>
                    <h2>Rp 15.750.000</h2>
                    <div class="card-mini-chart">
                        <div class="bar" style="height: 15px;"></div>
                        <div class="bar" style="height: 25px;"></div>
                        <div class="bar" style="height: 18px;"></div>
                        <div class="bar" style="height: 35px;"></div>
                        <div class="bar" style="height: 28px;"></div>
                        <div class="bar" style="height: 40px;"></div>
                        <div class="bar" style="height: 32px;"></div>
                    </div>
                </div>
                <div class="card-transactions">
                    <div class="card-tx">
                        <div class="card-tx-info">
                            <div class="card-tx-icon income">💰</div>
                            <div class="card-tx-details">
                                <span>Gaji Bulanan</span>
                                <small>25 Apr 2026</small>
                            </div>
                        </div>
                        <span class="card-tx-amount positive">+Rp 8.000.000</span>
                    </div>
                    <div class="card-tx">
                        <div class="card-tx-info">
                            <div class="card-tx-icon expense">🛒</div>
                            <div class="card-tx-details">
                                <span>Belanja Bulanan</span>
                                <small>24 Apr 2026</small>
                            </div>
                        </div>
                        <span class="card-tx-amount negative">-Rp 1.250.000</span>
                    </div>
                    <div class="card-tx">
                        <div class="card-tx-info">
                            <div class="card-tx-icon income">💼</div>
                            <div class="card-tx-details">
                                <span>Freelance Project</span>
                                <small>22 Apr 2026</small>
                            </div>
                        </div>
                        <span class="card-tx-amount positive">+Rp 3.500.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURES ===== --}}
<section class="section" id="features">
    <div class="section-header">
        <span class="section-label">Fitur Unggulan</span>
        <h2>Semua yang Kamu Butuhkan</h2>
        <p>Platform lengkap untuk mengelola keuangan bulananmu dengan fitur-fitur powerful dan mudah digunakan.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Dashboard Real-time</h3>
            <p>Pantau semua transaksi pemasukan dan pengeluaran secara real-time dengan tampilan yang intuitif.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📁</div>
            <h3>Kategori Cerdas</h3>
            <p>Kelompokkan transaksi berdasarkan kategori otomatis untuk analisis keuangan yang lebih akurat.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💹</div>
            <h3>Laporan & Analitik</h3>
            <p>Dapatkan insight mendalam tentang pola keuanganmu melalui grafik dan laporan terperinci.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🎯</div>
            <h3>Budget Planner</h3>
            <p>Atur budget bulanan dan dapatkan notifikasi saat pengeluaran mendekati batas yang ditentukan.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Keamanan Data</h3>
            <p>Data keuanganmu terenkripsi dan tersimpan aman dengan standar keamanan tingkat enterprise.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📱</div>
            <h3>Multi-Device</h3>
            <p>Akses dari mana saja — desktop, tablet, atau smartphone. Semua tersinkronisasi otomatis.</p>
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<div class="section-gray">
    <section class="section" id="how-it-works">
        <div class="section-header">
            <span class="section-label">Cara Kerja</span>
            <h2>Mulai dalam 4 Langkah Mudah</h2>
            <p>Tidak perlu ribet. Mulai kelola keuanganmu dalam hitungan menit.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Buat Akun</h4>
                <p>Daftar gratis hanya dengan email dan password. Tidak perlu kartu kredit.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Atur Kategori</h4>
                <p>Sesuaikan kategori pemasukan dan pengeluaran sesuai kebutuhanmu.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Catat Transaksi</h4>
                <p>Input setiap transaksi dengan cepat dan mudah melalui form yang simpel.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h4>Lihat Insight</h4>
                <p>Analisis pola keuanganmu dan buat keputusan finansial yang lebih baik.</p>
            </div>
        </div>
    </section>
</div>

{{-- ===== STATS BANNER ===== --}}
<section style="padding: 6rem 0;">
    <div class="stats-banner">
        <div class="stat-item">
            <h3>10,000+</h3>
            <p>Pengguna Terdaftar</p>
        </div>
        <div class="stat-item">
            <h3>500K+</h3>
            <p>Transaksi Tercatat</p>
        </div>
        <div class="stat-item">
            <h3>99.9%</h3>
            <p>Uptime Server</p>
        </div>
        <div class="stat-item">
            <h3>24/7</h3>
            <p>Customer Support</p>
        </div>
    </div>
</section>

{{-- ===== PRICING ===== --}}
<section class="section" id="pricing">
    <div class="section-header">
        <span class="section-label">Paket Harga</span>
        <h2>Pilih Paket yang Tepat</h2>
        <p>Mulai gratis dan upgrade kapan saja sesuai kebutuhanmu.</p>
    </div>
    <div class="pricing-grid" style="align-items: stretch;">
        {{-- Starter --}}
        <div class="pricing-card" style="display: flex; flex-direction: column;">
            <div class="pricing-header">
                <h3>Starter</h3>
                <div class="price">Gratis<span></span></div>
                <p class="price-note">Mulai sadar kemana uangmu pergi</p>
            </div>
            <ul class="pricing-features" style="flex: 1;">
                <li><span class="check">✓</span> Input transaksi (maks 50/bulan)</li>
                <li><span class="check">✓</span> Kategori dasar</li>
                <li><span class="check">✓</span> Laporan bulanan sederhana</li>
                <li><span class="check">✓</span> Total pemasukan & pengeluaran</li>
            </ul>
            <button class="btn-pricing" onclick="window.location='/register'">Mulai Gratis</button>
        </div>

        {{-- Personal --}}
        <div class="pricing-card popular" style="display: flex; flex-direction: column;">
            <span class="popular-badge">🔥 Paling Populer</span>
            <div class="pricing-header">
                <h3>Personal</h3>
                <div class="price">Rp 12K<span>/bulan</span></div>
                <p class="price-note">Atur pengeluaran biar tetap stabil</p>
            </div>
            <ul class="pricing-features" style="flex: 1;">
                <li><span class="check">✓</span> Transaksi unlimited</li>
                <li><span class="check">✓</span> Dashboard grafik basic</li>
                <li><span class="check">✓</span> Riwayat & Filter transaksi</li>
                <li><span class="check">✓</span> Laporan bulanan detail</li>
                <li><span class="check">✓</span> Budget planner</li>
            </ul>
            <button class="btn-pricing" onclick="window.location='/register'">Pilih Personal</button>
        </div>

        {{-- Professional --}}
        <div class="pricing-card" style="display: flex; flex-direction: column;">
            <div class="pricing-header">
                <h3>Professional</h3>
                <div class="price">Rp 25K<span>/bulan</span></div>
                <p class="price-note">Kontrol penuh + insight pintar</p>
            </div>
            <ul class="pricing-features" style="flex: 1;">
                <li><span class="check">✓</span> Semua fitur Personal</li>
                <li><span class="check">✓</span> Insight & Analisis cerdas</li>
                <li><span class="check">✓</span> Peringatan budget hampir habis</li>
                <li><span class="check">✓</span> Export data (CSV & PDF)</li>
                <li><span class="check">✓</span> Kategori custom unlimited</li>
            </ul>
            <button class="btn-pricing" onclick="window.location='/register'">Pilih Professional</button>
        </div>
    </div>
</section>

{{-- ===== TESTIMONIALS ===== --}}
<div class="section-gray">
    <section class="section" id="reviews">
        <div class="section-header">
            <span class="section-label">Ulasan Pengguna</span>
            <h2>Dipercaya Ribuan Pengguna</h2>
            <p>Apa kata mereka tentang pengalaman menggunakan Rantau Finance.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Rantau Finance benar-benar mengubah cara saya mengelola keuangan. Dulu selalu boros, sekarang bisa saving 30% setiap bulan!"
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AS</div>
                    <div class="testimonial-info">
                        <h5>Ahmad Surya</h5>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Dashboard-nya super intuitif! Saya bisa langsung lihat kemana uang saya pergi setiap bulan. Sangat recommended!"
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">DP</div>
                    <div class="testimonial-info">
                        <h5>Dina Pratiwi</h5>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Fitur budget planner-nya sangat membantu. Sekarang pengeluaran saya jauh lebih terkontrol. Best finance app!"
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">RH</div>
                    <div class="testimonial-info">
                        <h5>Rizky Hidayat</h5>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Sebagai freelancer, income saya tidak tetap. Rantau Finance membantu saya track semua pemasukan dari berbagai project."
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">MF</div>
                    <div class="testimonial-info">
                        <h5>Maya Fitriani</h5>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Saya pakai paket Enterprise untuk tim bisnis kecil saya. Fitur multi-user-nya sangat berguna untuk kolaborasi keuangan."
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">BW</div>
                    <div class="testimonial-info">
                        <h5>Budi Wicaksono</h5>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>
                    "Export laporan ke PDF sangat praktis untuk laporan keuangan bulanan. UI-nya juga cantik dan modern. Love it! 💕"
                </blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SN</div>
                    <div class="testimonial-info">
                        <h5>Sari Nurhaliza</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- ===== FAQ ===== --}}
<section class="section" id="faq">
    <div class="section-header">
        <span class="section-label">FAQ</span>
        <h2>Pertanyaan yang Sering Diajukan</h2>
        <p>Temukan jawaban atas pertanyaan umum tentang Rantau Finance.</p>
    </div>
    <div class="faq-list">
        <div class="faq-item active">
            <button class="faq-question" onclick="toggleFaq(this)">
                Apakah Rantau Finance benar-benar gratis?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                Ya! Paket Starter kami sepenuhnya gratis selamanya. Kamu bisa mencatat hingga 50 transaksi per bulan, mengakses laporan sederhana, dan mencatat total pemasukan tanpa biaya apapun.
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                Apakah data keuangan saya aman?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                Tentu saja. Kami menggunakan enkripsi SSL/TLS untuk transfer data dan enkripsi AES-256 untuk penyimpanan. Server kami diproteksi dengan firewall enterprise dan backup harian.
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                Bisa diakses dari smartphone?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                Ya, Rantau Finance sepenuhnya responsive dan bisa diakses melalui browser di smartphone, tablet, maupun desktop. Semua data tersinkronisasi secara otomatis.
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                Bagaimana cara upgrade paket?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                Kamu bisa upgrade kapan saja melalui halaman Settings di dashboard. Pembayaran mendukung transfer bank, e-wallet, dan kartu kredit/debit.
            </div>
        </div>
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                Apakah bisa export data transaksi?
                <span class="arrow">▼</span>
            </button>
            <div class="faq-answer">
                Fitur export data (CSV & PDF) hanya tersedia pada paket Professional, bersamaan dengan analisis insight otomatis dan laporan kategori detail.
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="cta-section">
    <div class="cta-content">
        <h2>Siap Mengelola Keuanganmu?</h2>
        <p>Bergabung dengan 10,000+ pengguna yang sudah merasakan kemudahan Rantau Finance. Daftar gratis sekarang.</p>
        <a href="/register" class="btn-primary-custom btn-lg">
            Mulai Sekarang —  Gratis
        </a>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <h3><img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance</h3>
            <p>Platform manajemen keuangan bulanan yang cerdas dan modern. Kelola pemasukan, pengeluaran, dan budget dengan mudah.</p>
        </div>
        <div class="footer-col">
            <h4>Produk</h4>
            <ul>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#pricing">Harga</a></li>
                <li><a href="#reviews">Ulasan</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Perusahaan</h4>
            <ul>
                <li><a href="/tentang-kami">Tentang Kami</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Karir</a></li>
                <li><a href="https://wa.me/6282271477947" target="_blank">Kontak</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Legal</h4>
            <ul>
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat & Ketentuan</a></li>
                <li><a href="#">Keamanan</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2026 Rantau Finance. All rights reserved.</p>
        <p>Made with ❤️ in Indonesia</p>
    </div>
</footer>

<script>
// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    navbar.classList.toggle('scrolled', window.scrollY > 50);
});

// FAQ toggle
function toggleFaq(btn) {
    const item = btn.parentElement;
    const wasActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
    if (!wasActive) item.classList.add('active');
}

// Scroll animations
const observerOpts = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOpts);

document.addEventListener('DOMContentLoaded', () => {
    const animItems = document.querySelectorAll('.feature-card, .step-card, .pricing-card, .testimonial-card, .faq-item, .stats-banner .stat-item');
    animItems.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `all 0.6s ease ${i % 4 * 0.1}s`;
        observer.observe(el);
    });
});
</script>
</body>
</html>
