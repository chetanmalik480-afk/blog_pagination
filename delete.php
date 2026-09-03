<?php

include "db.php";

if (!isset($_GET['id'])) {
    die("Post ID is missing.");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM posts WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo "Post deleted successfully!<br><br>";
    echo '<a href="read.php">View All Posts</a>';
} else {
    echo "Delete failed: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>