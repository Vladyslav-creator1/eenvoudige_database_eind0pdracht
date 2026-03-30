<?php
session_start();
require "db.php";
global $pdo;

$username = $_POST['username'];
$password = $_POST['password'];

// zoek gebruiker
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo "Login successful";
} else {
    echo "Wrong username or password";
}
?>
