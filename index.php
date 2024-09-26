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
<body class="bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-center text-white mb-10">Daftar Artikel</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
                        <div class="relative">
                            <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['judul']; ?>" class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent p-4 flex items-end">
                                <h2 class="font-semibold text-xl text-white"><?php echo $row['judul']; ?></h2>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-700 mb-4"><?php echo substr($row['deskripsi'], 0, 100) . '...'; ?></p>
                            <a href="detail.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-700">Tidak ada artikel yang dipublikasikan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
