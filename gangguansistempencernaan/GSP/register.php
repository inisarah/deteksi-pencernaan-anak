<?php
require 'dbconnection/koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Simpan password tanpa hashing (Tidak direkomendasikan!)
    $jenis_kelamin = $_POST['jenis_kelamin']; 
    $tanggal_lahir = $_POST['tanggal_lahir']; 
    $role = "pasien"; // Default role

    // Validasi input sederhana
    if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($jenis_kelamin) || empty($tanggal_lahir)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
        exit;
    }

    // Cek apakah username atau email sudah digunakan
    $cek = $conn->prepare("SELECT * FROM tabel_akun WHERE username = ? OR email = ?");
    $cek->execute([$username, $email]);

    if ($cek->rowCount() > 0) {
        echo "<script>alert('Username atau Email sudah terdaftar!');</script>";
    } else {
        // Simpan ke database tanpa hashing password
        $stmt = $conn->prepare("INSERT INTO tabel_akun (role, nama_lengkap, username, email, password, jenis_kelamin, tanggal_lahir) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$role, $nama_lengkap, $username, $email, $password, $jenis_kelamin, $tanggal_lahir])) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Gagal mendaftar!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Pakar Gangguan Pencernaan Anak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #fff7e6;
            font-family: 'Poppins', sans-serif;
        }
        .header {
            background: #ff9800;
        }
        .button {
            transition: transform 0.3s ease;
        }
        .button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="header text-white py-4 shadow-md">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/gambar/logo.png" alt="Logo" class="w-12 h-12 mr-3">
                <h1 class="text-xl font-bold">Sistem Pakar Gangguan Pencernaan Anak</h1>
            </div>
            <div>
                <a href="index.php" class="text-white px-4 hover:underline">Home</a>
                <a href="login.php" class="text-white px-4 hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Form Register -->
    <div class="container mx-auto flex justify-center items-center min-h-screen mt-5">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-orange-600 mb-6">Daftar Akun</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label class="block font-semibold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Username</label>
                    <input type="text" name="username" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Email</label>
                    <input type="email" name="email" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Password</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full p-2 border rounded">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" required class="w-full p-2 border rounded">
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700">Daftar</button>
                <p class="text-sm mt-3 text-center">Sudah punya akun? <a href="login.php" class="text-orange-600 font-semibold">Login</a></p>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 py-3 mt-3">
        <div class="container mx-auto text-center">
            <p class="text-gray-600 text-sm">&copy; 2025 Luthfi Maisarah. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
