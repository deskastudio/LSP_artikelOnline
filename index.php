<?php
include 'koneksi.php';

$sql = "SELECT * FROM artikel WHERE published = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/style.css" rel="stylesheet">
    <title>Artikel Online</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Artikel</h1>
        <div class="bg-white p-4 rounded shadow">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="mb-4">
                        <h2 class="font-semibold"><?php echo $row['judul']; ?></h2>
                        <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['judul']; ?>" class="w-32 h-32 object-cover mb-2">
                        <p><?php echo substr($row['deskripsi'], 0, 100) . '...'; ?></p>
                        <a href="detail.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="text-blue-500">Lihat Detail</a>
                        <hr class="my-2">
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada artikel yang dipublikasikan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
