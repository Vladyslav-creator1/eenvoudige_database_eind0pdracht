<?php
session_start();
?>

<!DOCTYPE html>
<html>
<link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<head>


    <title>Login</title>
</head>
<body>
<div class="card mb-4">

    <div class="card-body" style="
  display: grid;
  place-items: center;
  height: 90vh;
">
<form method="POST" action="auth.php">
    <input type="text" name="username" placeholder="Login" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="add" class="btn btn-success">Login</button>
</form>

</body>
</html>