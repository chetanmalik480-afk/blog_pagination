<?php
session_start();
include 'db.php';
$error = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $id      = (int)$_POST['id'];

    if (empty($title) || empty($content)) {
        $error = "Title and content cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        header("Location: read.php");
        exit();
    }
}

// Fetch existing post to pre-fill the form
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
    <h2>Edit Post</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required minlength="3"
                   value="<?php echo htmlspecialchars($post['title']); ?>">
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" required minlength="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-warning">Update Post</button>
    </form>
</div>
</body>
</html>
