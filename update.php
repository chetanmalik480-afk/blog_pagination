<?php

include "db.php";

if (!isset($_GET['id'])) {
    die("Post ID is missing.");
}

$id = intval($_GET['id']);

// Get existing post
$sql = "SELECT id, title, content FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$post = mysqli_fetch_assoc($result);

if (!$post) {
    die("Post not found.");
}


// Update post
if (isset($_POST['update'])) {

    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Post updated successfully!<br><br>";
        echo '<a href="read.php">View Posts</a>';
        exit;
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Post</title>
</head>

<body>

<h1>Update Post</h1>

<form method="POST">

    <label>Title:</label><br>
    <input 
        type="text" 
        name="title" 
        value="<?php echo htmlspecialchars($post['title']); ?>"
        required
    >

    <br><br>

    <label>Content:</label><br>

    <textarea 
        name="content" 
        rows="8" 
        cols="50"
        required
    ><?php echo htmlspecialchars($post['content']); ?></textarea>

    <br><br>

    <button type="submit" name="update">Update Post</button>

</form>

<br>

<a href="read.php">View All Posts</a>

</body>
</html>