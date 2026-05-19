<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami — Rantau Finance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_RD.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .about-hero {
            padding: 8rem 2rem 4rem;
            text-align: center;
            background: linear-gradient(180deg, #f0f0ff 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .about-hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .about-hero p {
            font-size: 1.15rem;
            color: var(--gray);
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto;
        }

        .mission-section {
            padding: 6rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .mission-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }

        .mission-content p {
            font-size: 1.1rem;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .mission-visual {
            background: var(--gradient-1);
            border-radius: 24px;
            padding: 3rem;
            color: white;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .mission-visual h3 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .team-section {
            padding: 4rem 2rem 8rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .team-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 3rem;
            color: var(--dark);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .team-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .team-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .team-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .team-card p {
            font-size: 0.95rem;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .mission-section {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .about-hero h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar" id="navbar">
        <a href="/" class="nav-logo"><img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance</a>
        <ul class="nav-links">
            <li><a href="/#features">Fitur</a></li>
            <li><a href="/#pricing">Harga</a></li>
            <li><a href="/#reviews">Ulasan</a></li>
            <li><a href="/#faq">FAQ</a></li>
        </ul>
        <div class="nav-cta">
            <a href="/login" class="btn-outline-custom">Masuk</a>
            <a href="/register" class="btn-primary-custom">Daftar</a>
        </div>
    </nav>

    <section class="about-hero">
        <h1>Misi Kami untuk <br><span class="gradient-text">Milenial Indonesia</span></h1>
        <p>Kami hadir untuk membantu mahasiswa, perantau, dan profesional muda Indonesia dalam mengelola keuangan mereka agar lebih cerdas dan stabil.</p>
    </section>

    <section class="mission-section">
        <div class="mission-content">
            <h2>Berawal dari Masalah Sehari-hari</h2>
            <p>Banyak dari kita yang sering bertanya-tanya, "Kemana perginya gaji bulan ini?". Manajemen keuangan seringkali terasa rumit, membosankan, dan sulit untuk dipertahankan secara konsisten.</p>
            <p>Rantau Finance diciptakan sebagai solusi modern. Kami menggabungkan kemudahan pencatatan dengan visualisasi data yang memukau agar kamu bisa menikmati proses mengatur keuanganmu.</p>
        </div>
        <div class="mission-visual">
            <h3>Visi Kami</h3>
            <p>Menciptakan generasi muda Indonesia yang melek finansial, bebas dari stres keuangan, dan siap mencapai kemerdekaan finansial di masa depan.</p>
        </div>
    </section>

    <section class="team-section">
        <h2>Tim di Balik Rantau Finance</h2>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-avatar" style="position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <span style="position: absolute; z-index: 1;">AS</span>
                    <img src="{{ asset('images/foto_alvin.jpeg') }}" alt="Alvin Setiawan" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center 15%; z-index: 2;" onerror="this.style.display='none'">
                </div>
                <h4>Alvin Setiawan</h4>
                <p>Co-Founder & Creator</p>
            </div>
            <div class="team-card">
                <div class="team-avatar" style="position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <span style="position: absolute; z-index: 1;">CD</span>
                    <img src="{{ asset('images/clara.jpg') }}" alt="Clara Dian Ajeng Saputri" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;" onerror="this.style.display='none'">
                </div>
                <h4>Clara Dian Ajeng Saputri</h4>
                <p>Co-Founder & Creator</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-content">
            <h2>Siap Mengubah Finansialmu?</h2>
            <p>Bergabung bersama kami dan mulai kendalikan uangmu sekarang juga.</p>
            <a href="/register" class="btn-primary-custom btn-lg">Daftar Gratis</a>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3><img src="{{ asset('images/logo_RD.png') }}" style="height: 48px; vertical-align: middle;"> RantauFinance</h3>
                <p>Platform manajemen keuangan bulanan yang cerdas dan modern. Kelola pemasukan, pengeluaran, dan budget dengan mudah.</p>
            </div>
            <div class="footer-col">
                <h4>Produk</h4>
                <ul>
                    <li><a href="/#features">Fitur</a></li>
                    <li><a href="/#pricing">Harga</a></li>
                    <li><a href="/#reviews">Ulasan</a></li>
                    <li><a href="/#faq">FAQ</a></li>
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
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
</body>

</html>