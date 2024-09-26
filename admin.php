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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Halaman Admin</title>
    <style>
        /* CSS untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            max-width: 90%;
            max-height: 90%;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-purple-400 to-blue-500 min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Halaman Admin</h1>

        <!-- Tombol untuk tambah artikel -->
        <div class="text-center mb-6">
            <a href="add.php" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-transform transform hover:scale-105">Tambah Artikel</a>
        </div>

        <h2 class="text-2xl font-semibold mb-4 text-gray-800 text-center">Daftar Artikel</h2>

        <!-- Tabel responsif -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-3 px-6 border-b">Judul</th>
                        <th class="py-3 px-6 border-b">Deskripsi</th>
                        <th class="py-3 px-6 border-b">Foto</th>
                        <th class="py-3 px-6 border-b">Aksi</th>
                        <th class="py-3 px-6 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-100 transition-colors duration-200">
                                <td class="py-3 px-6 border-b"><?php echo $row['judul']; ?></td>
                                <td class="py-3 px-6 border-b"><?php echo substr($row['deskripsi'], 0, 100); ?>...</td>
                                <td class="py-3 px-6 border-b">
                                    <img src="uploads/<?php echo $row['foto']; ?>" 
                                         alt="<?php echo $row['judul']; ?>" 
                                         class="rounded-full object-cover cursor-pointer"
                                         onclick="openModal('uploads/<?php echo $row['foto']; ?>')" width="50">
                                </td>
                                <td class="py-3 px-6 border-b">
                                    <a href="edit.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="delete.php?id_artikel=<?php echo $row['id_artikel']; ?>" class="text-red-500 hover:text-red-700 ml-2">Hapus</a>
                                </td>
                                <td class="py-3 px-6 border-b">
                                    <form action="update_status.php" method="POST" class="inline-block">
                                        <input type="hidden" name="id_artikel" value="<?php echo $row['id_artikel']; ?>">
                                        <label class="inline-flex items-center mr-2">
                                            <input type="radio" name="status" value="published" <?php echo $row['published'] ? 'checked' : ''; ?>>
                                            <span class="ml-1">Publish</span>
                                        </label>
                                        <label class="inline-flex items-center mr-2">
                                            <input type="radio" name="status" value="draft" <?php echo !$row['published'] ? 'checked' : ''; ?>>
                                            <span class="ml-1">Draft</span>
                                        </label>
                                        <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition-transform transform hover:scale-105">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-4 px-6 border-b text-center text-gray-600">Tidak ada artikel tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-6">
            <a href="logout.php" class="text-red-500 hover:text-red-700 text-lg font-semibold">Logout</a>
        </div>
    </div>

    <!-- Modal untuk menampilkan gambar besar -->
    <div id="myModal" class="modal" onclick="closeModal()">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <script>
        function openModal(src) {
            const modal = document.getElementById("myModal");
            const img = document.getElementById("img01");
            img.src = src;
            modal.style.display = "flex";
        }

        function closeModal() {
            const modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
