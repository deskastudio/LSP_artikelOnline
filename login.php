<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil password dari database (teks biasa)
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Hash password yang dimasukkan user
    $hashedInputPassword = password_hash($password, PASSWORD_DEFAULT);

    // Periksa apakah password yang diinputkan cocok dengan password yang disimpan
    if ($password === $stored_password) {
        // Password cocok, login berhasil
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit();
    } else {
        // Password salah
        echo "Password salah.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/style.css" rel="stylesheet">
    <title>Login Admin</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Login Admin</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-2 mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block">Username</label>
                <input type="text" name="username" id="username" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="password" class="block">Password</label>
                <input type="password" name="password" id="password" required class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2">Login</button>
        </form>
    </div>
</body>
</html>