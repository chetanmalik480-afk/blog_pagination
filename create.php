<?php

include "db.php";

if (isset($_POST['submit'])) {

    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $title, $content);

    if (mysqli_stmt_execute($stmt)) {
        echo "Post created successfully!";
        echo "<br><br>";
        echo '<a href="read.php">View Posts</a>';
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>

<body>

<h1>Create New Post</h1>

<form method="POST">

    <label>Title:</label><br>
    <input type="text" name="title" required>

    <br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="8" cols="50" required></textarea>

    <br><br>

    <button type="submit" name="submit">Create Post</button>

</form>

<br>

<a href="read.php">View All Posts</a>

</body>
</html>