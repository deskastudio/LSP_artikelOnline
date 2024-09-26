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

    // Periksa apakah password yang diinputkan cocok dengan password yang disimpan
    if ($password === $stored_password) {
        // Password cocok, login berhasil
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit();
    } else {
        // Password salah
        $error = "Password atau username salah.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Login Admin</title>
</head>
<body class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 min-h-screen flex items-center justify-center">
    <div class="container max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Login Admin</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-2 mb-4 rounded-md"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-6">
                <label for="username" class="block text-gray-700 font-semibold">Username</label>
                <input type="text" name="username" id="username" required class="border border-gray-300 p-3 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" name="password" id="password" required class="border border-gray-300 p-3 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-purple-500 text-white py-3 px-6 rounded-md hover:bg-purple-600 transition-transform transform hover:scale-105">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
