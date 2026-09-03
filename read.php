<?php
include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit  = 5; // posts per page
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $sql      = "SELECT * FROM posts WHERE title LIKE '%$s%' OR content LIKE '%$s%' ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $countSql = "SELECT COUNT(*) AS total FROM posts WHERE title LIKE '%$s%' OR content LIKE '%$s%'";
} else {
    $sql      = "SELECT * FROM posts ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $countSql = "SELECT COUNT(*) AS total FROM posts";
}

$result      = $conn->query($sql);
$totalRows   = $conn->query($countSql)->fetch_assoc()['total'];
$totalPages  = max(1, ceil($totalRows / $limit));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blog Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">Blog Posts</h2>
    <a href="create.php" class="btn btn-success mb-3">Add New Post</a>

    <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2"
               placeholder="Search by title or content"
               value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <table class="table table-bordered table-striped">
        <tr><th>Title</th><th>Content</th><th>Created At</th><th>Actions</th></tr>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="4" class="text-center">No posts found.</td></tr>
        <?php else: while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars(substr($row['content'], 0, 50)); ?>...</td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this post?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; endif; ?>
    </table>

    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>