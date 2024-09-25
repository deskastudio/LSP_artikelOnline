<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/styles.css" rel="stylesheet">
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

<?php
$stmt->close();
$conn->close();
?>
