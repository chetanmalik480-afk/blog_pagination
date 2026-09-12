<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Only admins can delete posts.");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: read.php");
exit();
?>
