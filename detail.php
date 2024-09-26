<?php
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/style.css" rel="stylesheet">
    <title>Detail Artikel</title>
</head>
<body class="bg-gradient-to-r from-green-400 to-blue-500">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo $artikel['judul']; ?></h1>
            <img src="uploads/<?php echo $artikel['foto']; ?>" alt="<?php echo $artikel['judul']; ?>" class="w-full h-64 object-cover rounded-lg mb-4">
            <p class="text-gray-700 mb-6"><?php echo $artikel['deskripsi']; ?></p>
            <a href="index.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Kembali ke Daftar Artikel</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
