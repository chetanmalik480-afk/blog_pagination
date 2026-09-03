<?php
session_start();
include(__DIR__ . "/db.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $username;
            header("Location: read.php");
        } else {
            echo "Wrong password!";
        }
    } else {
        echo "User not found!";
    }
}
?>

<form method="POST">
    <h2>Login</h2>
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button name="login">Login</button>
</form>