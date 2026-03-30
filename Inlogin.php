<?php
session_start();
?>

<!DOCTYPE html>
<html>
<link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    head,body
    {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
</style>
<head>


    <title>Login</title>
</head>
<body>
<div class="card mb-4">

    <div class="card-body">
<form method="POST" action="Edit.php">
    <input type="text" name="username" placeholder="Login" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="add" class="btn btn-success">Login</button>
</form>

</body>
</html>