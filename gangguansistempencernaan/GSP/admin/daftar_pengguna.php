<?php
session_start();
require '../dbconnection/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$query = $conn->query("SELECT * FROM tabel_akun");
$pengguna = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $nama = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $role = "pasien";
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }

    if ($id) {
        if (!empty($_POST['password'])) {
            $stmt = $conn->prepare("UPDATE tabel_akun SET nama_lengkap=?, username=?, email=?, password=?, tanggal_lahir=? WHERE id_akun=?");
            $stmt->execute([$nama, $username, $email, $password, $tanggal_lahir, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE tabel_akun SET nama_lengkap=?, username=?, email=?, tanggal_lahir=? WHERE id_akun=?");
            $stmt->execute([$nama, $username, $email, $tanggal_lahir, $id]);
        }
        echo "<script>alert('Pengguna berhasil diperbarui!'); window.location.href='daftar_pengguna.php';</script>";
    } else {
        $cekUser = $conn->prepare("SELECT * FROM tabel_akun WHERE username = ?");
        $cekUser->execute([$username]);
        if ($cekUser->rowCount() > 0) {
            echo "<script>alert('Username sudah digunakan!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO tabel_akun (nama_lengkap, username, email, password, tanggal_lahir) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $username, $email, $password, $tanggal_lahir]);
            echo "<script>alert('Pengguna berhasil ditambahkan!'); window.location.href='daftar_pengguna.php';</script>";
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM tabel_akun WHERE id_akun = ?");
    $stmt->execute([$id]);
    echo "<script>alert('Pengguna berhasil dihapus!'); window.location.href='daftar_pengguna.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-100 flex">
    <?php include '../assets/sidebar/sidebar_admin.php'; ?>
    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-orange-700 mb-6">Kelola Pengguna</h1>
        <button onclick="toggleForm()" class="bg-orange-500 text-white px-4 py-2 rounded shadow-md hover:bg-orange-600">
            + Tambah Pengguna
        </button>
        <div class="bg-white p-6 rounded-lg shadow-md mt-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Nama Lengkap</th>
                        <th class="p-3 text-left">Username</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Tanggal Lahir</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengguna as $user): ?>
                        <tr class="border-b hover:bg-orange-200">
                            <td class="p-3"><?php echo $user['id_akun']; ?></td>
                            <td class="p-3"><?php echo $user['nama_lengkap']; ?></td>
                            <td class="p-3"><?php echo $user['username']; ?></td>
                            <td class="p-3"><?php echo $user['email']; ?></td>
                            <td class="p-3"><?php echo $user['tanggal_lahir']; ?></td>
                            <td class="p-3 text-center">
                                <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" 
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</button>
                                <a href="?hapus=<?php echo $user['id_akun']; ?>" 
                                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="formTambah" class="hidden bg-white p-6 rounded-lg shadow-md mt-6">
            <h2 class="text-xl font-bold text-orange-700 mb-4">Form Pengguna</h2>
            <form method="POST">
                <input type="hidden" name="id" id="userId">
                <label class="block text-orange-700">Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" id="namaLengkap" required class="w-full p-2 border rounded-md">
                <label class="block text-orange-700">Username:</label>
                <input type="text" name="username" id="username" required class="w-full p-2 border rounded-md">
                <label class="block text-orange-700">Email:</label>
                <input type="email" name="email" id="email" required class="w-full p-2 border rounded-md">
                <label class="block text-orange-700">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" id="tanggalLahir" required class="w-full p-2 border rounded-md">
                <label class="block text-orange-700">Password:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded-md">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 mt-4 rounded shadow-md">Simpan</button>
            </form>
        </div>
    </main>
</body>
<script>
    function toggleForm() {
        document.getElementById("formTambah").classList.toggle("hidden");
    }
    function editUser(user) {
        toggleForm();
        document.getElementById("userId").value = user.id_akun;
        document.getElementById("namaLengkap").value = user.nama_lengkap;
        document.getElementById("username").value = user.username;
        document.getElementById("email").value = user.email;
        document.getElementById("tanggalLahir").value = user.tanggal_lahir;
    }
</script>
</html>
