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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Edit Artikel</title>
</head>
<body class="bg-gradient-to-r from-green-400 to-blue-500 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-lg max-w-xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Edit Artikel</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="judul" class="block text-gray-700 font-semibold">Judul</label>
                <input type="text" name="judul" id="judul" value="<?php echo $artikel['judul']; ?>" required class="border border-gray-300 p-3 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-700 font-semibold">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required class="border border-gray-300 p-3 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-32"><?php echo $artikel['deskripsi']; ?></textarea>
            </div>
            <div class="mb-6">
                <label for="foto" class="block text-gray-700 font-semibold">Foto (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="border border-gray-300 p-3 w-full rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="published" class="form-checkbox" <?php echo $artikel['published'] ? 'checked' : ''; ?>>
                    <span class="ml-2 text-gray-700 font-semibold">Publish Artikel</span>
                </label>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white py-3 px-6 rounded-md hover:bg-blue-600 transition-transform transform hover:scale-105">Update Artikel</button>
            </div>
        </form>
        <div class="text-center mt-4">
            <a href="admin.php" class="text-gray-500 hover:text-gray-700">Kembali ke Admin</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
