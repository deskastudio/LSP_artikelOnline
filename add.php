<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $foto = $_FILES['foto']['name'];
    $username = $_SESSION['username'];

    // Upload foto
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);

    $sql = "INSERT INTO artikel (username, judul, foto, deskripsi) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $judul, $foto, $deskripsi);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/style.css" rel="stylesheet">
    <title>Tambah Artikel</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tambah Artikel</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="judul" class="block">Judul</label>
                <input type="text" name="judul" id="judul" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required class="border p-2 w-full"></textarea>
            </div>
            <div class="mb-4">
                <label for="foto" class="block">Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" required class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2">Tambah Artikel</button>
        </form>
        <a href="admin.php" class="text-blue-500 mt-4 inline-block">Kembali ke Halaman Admin</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
