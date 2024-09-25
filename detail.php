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
    <link href="dist/styles.css" rel="stylesheet">
    <title>Detail Artikel</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4"><?php echo $artikel['judul']; ?></h1>
        <img src="uploads/<?php echo $artikel['foto']; ?>" alt="<?php echo $artikel['judul']; ?>" class="w-64 h-64 object-cover mb-2">
        <p><?php echo $artikel['deskripsi']; ?></p>
        <a href="index.php" class="text-blue-500">Kembali ke Daftar Artikel</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
