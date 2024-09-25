<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id_artikel = $_GET['id_artikel'];

$sql = "DELETE FROM artikel WHERE id_artikel = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_artikel);
$stmt->execute();

header("Location: admin.php");
exit();

$stmt->close();
$conn->close();
?>
