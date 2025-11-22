<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Gangguan Pencernaan Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <style>
        body {
            background: #fdf6e3;
            font-family: 'Poppins', sans-serif;
        }
        .header {
            background: linear-gradient(90deg, #ff9800, #ffcc80);
        }
        .button {
            transition: transform 0.3s ease, background 0.3s;
        }
        .button:hover {
            transform: scale(1.1);
            background: #ff7043;
        }
        .content-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2rem;
            margin-top:4rem;
        }
        .left-content {
            width: 50%;
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .swiper-container {
            width: 100%;
            max-width: 400px;
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            margin-left:4rem;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #ff9800;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="header text-white py-3 shadow-md">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/gambar/logo.png" alt="Logo 2" class="w-12 h-12 mr-3">
                <h1 class="text-2xl font-bold">Sistem Pakar Gangguan Pencernaan Anak</h1>
            </div>
            <div>
                <a href="index.php" class="text-white px-4 hover:underline">Home</a>
                <a href="login.php" class="text-white px-4 hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto content-container">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="assets/gambar/gsp1.jpeg" alt="Ilustrasi Anak Sehat"></div>
                    <div class="swiper-slide"><img src="assets/gambar/gsp2.jpeg" alt="Ilustrasi Anak Ceria"></div>
                    <div class="swiper-slide"><img src="assets/gambar/gsp3.jpg" alt="Ilustrasi Anak Sehat"></div>
                    <div class="swiper-slide"><img src="assets/gambar/gsp4.jpg" alt="Ilustrasi Anak Sehat"></div>
                    <div class="swiper-slide"><img src="assets/gambar/gsp5.jpg" alt="Ilustrasi Anak Sehat"></div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <div class="right-content w-1/2">
            <h2 class="text-3xl font-bold text-orange-600 mb-4">Kenali Gangguan Pencernaan pada Anak</h2>
            <p class="text-gray-700 leading-relaxed mb-4">Gangguan pencernaan pada anak dapat menyebabkan ketidaknyamanan dan mempengaruhi tumbuh kembang mereka. Dengan sistem pakar ini, Anda dapat mendiagnosis kondisi dengan lebih cepat dan akurat.</p>
            <p class="text-gray-700 leading-relaxed">Dapatkan saran dan rekomendasi terbaik untuk menjaga kesehatan pencernaan si kecil.</p>
            <a href="login.php" class="button mt-6 inline-block bg-orange-500 text-white font-medium px-6 py-3 rounded-lg shadow-md">
                Ayo Diagnosa Sekarang!
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-orange-100 py-3 mt-24">
        <div class="container mx-auto text-center">
            <p class="text-gray-600 text-sm">&copy; 2025 Luthfi Maisarah. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            autoplay: { delay: 3000, disableOnInteraction: false }
        });
    </script>
</body>
</html>
