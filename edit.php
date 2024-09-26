<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id_artikel = $_GET['id_artikel'];
$sql = "SELECT * FROM artikel WHERE id_artikel = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $artikel = $result->fetch_assoc();
} else {
    die("Artikel tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $published = isset($_POST['published']) ? 1 : 0;

    // Cek jika ada foto yang diupload
    if ($_FILES['foto']['name']) {
        $foto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);

        $sql = "UPDATE artikel SET judul = ?, foto = ?, deskripsi = ?, published = ? WHERE id_artikel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $judul, $foto, $deskripsi, $published, $id_artikel);
    } else {
        $sql = "UPDATE artikel SET judul = ?, deskripsi = ?, published = ? WHERE id_artikel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $judul, $deskripsi, $published, $id_artikel);
    }
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
    <title>Edit Artikel</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Artikel</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="judul" class="block">Judul
                </label>
                <input type="text" name="judul" id="judul" value="<?php echo $artikel['judul']; ?>" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required class="border p-2 w-full"><?php echo $artikel['deskripsi']; ?></textarea>
            </div>
            <div class="mb-4">
                <label for="foto" class="block">Foto (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="published" class="form-checkbox" <?php echo $artikel['published'] ? 'checked' : ''; ?>>
                    <span class="ml-2">Publish Artikel</span>
                </label>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2">Update Artikel</button>
        </form>
        <a href="admin.php" class="text-gray-500">Kembali ke Admin</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
