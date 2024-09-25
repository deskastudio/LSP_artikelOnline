<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil data artikel
$sql = "SELECT * FROM artikel WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

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
    <link href="dist/styles.css" rel="stylesheet">
    <title>Admin</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Halaman Admin</h1>
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

        <h2 class="text-xl font-semibold">Daftar Artikel</h2>
        <div class="bg-white p-4 rounded shadow">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="mb-4">
                        <h3 class="font-semibold"><?php echo $row['judul']; ?></h3>
                        <p><?php echo $row['deskripsi']; ?></p>
                        <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['judul']; ?>" class="w-32 h-32 object-cover mb-2">
                        <a href="edit.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="text-blue-500">Edit</a>
                        <a href="delete.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="text-red-500 ml-2">Hapus</a>
                        <hr class="my-2">
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada artikel tersedia.</p>
            <?php endif; ?>
        </div>
        <a href="logout.php" class="text-red-500">Logout</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
