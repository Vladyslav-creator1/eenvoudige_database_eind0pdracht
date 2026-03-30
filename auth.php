<?php
session_start();


$valid_user = "admin";
$valid_pass = "1234";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['user'] = $username;

    // редирект на главную страницу
    header("Location: index.php");
    exit();
} else {
    echo "Неверный логин или пароль";
}