<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_artikel = $_POST['id_artikel'];
    $status = $_POST['status'];

    // Tentukan status berdasarkan tombol yang ditekan
    $published = ($status == 'published') ? 1 : 0;

    $sql = "UPDATE artikel SET published = ? WHERE id_artikel = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $published, $id_artikel);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>
