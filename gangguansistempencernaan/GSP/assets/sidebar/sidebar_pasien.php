<!-- Sidebar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<aside class="w-64 bg-orange-400 min-h-screen text-white fixed top-0 left-0 shadow-lg flex flex-col">
    <div class="p-6 flex flex-col items-center">
        <!-- Logo dengan animasi goyang -->
        <img src="../assets/gambar/logo.png" alt="Logo" class="w-20 h-20 mb-1 animate-bounce">
        <h1 class="text-xl font-bold text-center">Sistem Pakar Gangguan Pencernaan Pada Anak</h1>
    </div>

    <!-- Menu Sidebar -->
    <nav class="mt-1 flex-grow">
        <ul>
            <li class="hover:bg-orange-500 transition duration-300 flex items-center px-6 py-3 rounded-lg">
                <i class="fas fa-home mr-3"></i> <a href="index.php">Dashboard</a>
            </li>
            <li class="hover:bg-orange-500 transition duration-300 flex items-center px-6 py-3 rounded-lg">
                <i class="fas fa-stethoscope mr-3"></i> <a href="../pasien/konsultasifc.php">Konsultasi</a>
            </li>
            <li class="hover:bg-orange-500 transition duration-300 flex items-center px-6 py-3 rounded-lg">
                <i class="fas fa-folder-open mr-3"></i> <a href="../pasien/rekam_medis.php">Rekam Medis</a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="mb-5 px-6">
        <a href="../logout.php" class="block text-center bg-red-500 hover:bg-red-600 px-6 py-3 transition duration-300 rounded-lg shadow-lg flex items-center justify-center gap-2 text-lg font-semibold animate-pulse">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
    </div>
</aside>

<!-- Tambahkan Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
