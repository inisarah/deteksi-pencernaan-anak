<?php
session_start();
require '../dbconnection/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Ambil jawaban unik dari tabel cf_user tanpa duplikasi
$query = $conn->query("SELECT DISTINCT jawaban_user, cf_user FROM cf_user ORDER BY cf_user ASC");
$cf_data = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen CF User - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_admin.php'; ?>

    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-gray-700 text-center mb-6"> Certainty Factor (CF) User</h1>

        <!-- Tabel CF User -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-4">
            <h2 class="text-xl font-bold text-gray-700 text-center mb-4">List CF User</h2>
            <table class="w-full border-collapse text-center">
                <thead>
                    <tr class="bg-orange-600 text-white">
                        <th class="p-3 text-center w-1/2">Jawaban User</th>
                        <th class="p-3 text-center w-1/2">Nilai CF User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cf_data as $item): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-3 text-center"><?php echo htmlspecialchars($item['jawaban_user']); ?></td>
                            <td class="p-3 text-center"><?php echo $item['cf_user']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
