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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/style.css" rel="stylesheet">
    <title>Admin</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Halaman Admin</h1>

        <!-- Tombol untuk tambah artikel -->
        <a href="add.php" class="bg-green-500 text-white p-2 mb-4 inline-block">Tambah Artikel</a>

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

                        <!-- Form untuk memilih status publish/draft -->
                        <form action="update_status.php" method="POST" class="inline-block mt-2">
                            <input type="hidden" name="id_artikel" value="<?php echo $row['id_artikel']; ?>">
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="status" value="published" <?php echo $row['published'] ? 'checked' : ''; ?>>
                                <span class="ml-2">Publish</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="draft" <?php echo !$row['published'] ? 'checked' : ''; ?>>
                                <span class="ml-2">Draft</span>
                            </label>
                            <button type="submit" class="bg-blue-500 text-white p-1 ml-4">Simpan</button>
                        </form>
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
