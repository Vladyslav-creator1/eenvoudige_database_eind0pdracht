<?php

require "db.php";
global $pdo;

$username = $_POST['username'];
$password = $_POST['password'];

// secret sql besherm
$hash = password_hash($password, PASSWORD_BCRYPT);

// save
$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

echo "User registered";