<?php
session_start();
require 'dbconnection/koneksi.php'; // Hubungkan dengan database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Ambil pilihan role dari form

    // Cek username dan role di database
    $stmt = $conn->prepare("SELECT * FROM tabel_akun WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stored_password = $user['password'];

        // Cek apakah password sudah di-hash atau tidak
        if (password_verify($password, $stored_password) || $password === $stored_password) {
            // Simpan data user ke session
            $_SESSION['id_akun'] = $user['id_akun'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

            // Redirect berdasarkan role
            if ($role == "admin") {
                header("Location: admin/index.php");
            } elseif ($role == "pasien") {
                header("Location: pasien/index.php");
            }
            exit;
        }
    }

    echo "<script>alert('Username, Password, atau Role salah!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pakar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fce6b1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 380px;
            text-align: center;
        }
        .login-container h3 {
            color: #ff7300;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-login {
            background: #ff7300;
            color: white;
            border-radius: 10px;
            padding: 10px;
            font-weight: bold;
        }
        .btn-login:hover {
            background: #e66400;
        }
        .role-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .role-container label {
            margin: 0 10px;
            display: flex;
            align-items: center;
            font-size: 16px;
            cursor: pointer;
        }
        .role-container input {
            margin-right: 5px;
        }
        .role-container img {
            width: 25px;
            height: 25px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Login</h3>
        <form action="" method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <div class="role-container">
                <label>
                    <input type="radio" name="role" value="admin" required>
                    Admin 
                </label>
                <label>
                    <input type="radio" name="role" value="pasien" required>
                    Pasien 
                </label>
            </div>
            <button type="submit" class="btn btn-login btn-block">Login</button>
        </form>
        <p class="mt-3">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>
