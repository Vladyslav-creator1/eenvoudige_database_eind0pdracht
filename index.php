<?php
global $pdo;
require 'db.php';

// Получаем всех клиентов
$stmt = $pdo->query("SELECT * FROM usersdb");
$clients = $stmt->fetchAll();

// Добавление нового клиента
if (isset($_POST['add'])) {
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];
    $woonplaats = $_POST['woonplaats'];

    $stmt = $pdo->prepare("INSERT INTO usersdb (naam, email, adres, woonplaats) VALUES (?, ?, ?, ?)");
    $stmt->execute([$naam, $email, $adres, $woonplaats]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Удаление клиента
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM usersdb WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>CRM - Clients</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .add-form { margin-bottom: 20px; padding: 15px; background: #f9f9f9; }
        .add-form input { padding: 8px; margin-right: 10px; margin-bottom: 10px; }
        .add-form button { padding: 8px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .delete { color: red; text-decoration: none; margin-left: 10px; }
    </style>
</head>
<body>
<h1>CRM - Clients</h1>

<!-- Form to add new client -->
<div class="add-form">
    <h3>Nieuwe klant toevoegen</h3>
    <form method="POST">
        <input type="text" name="naam" placeholder="Naam" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="adres" placeholder="Adres" required>
        <input type="text" name="woonplaats" placeholder="Woonplaats" required>
        <button type="submit" name="add">Toevoegen</button>
    </form>
</div>

<!-- Clients table -->
<table>
    <tr>
        <th>ID</th>
        <th>Naam</th>
        <th>Email</th>
        <th>Adres</th>
        <th>Woonplaats</th>
        <th>Gemaakt op</th>
        <th>Actie</th>
    </tr>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?= $client['id'] ?></td>
            <td><?= htmlspecialchars($client['naam']) ?></td>
            <td><?= htmlspecialchars($client['email']) ?></td>
            <td><?= htmlspecialchars($client['adres']) ?></td>
            <td><?= htmlspecialchars($client['woonplaats']) ?></td>
            <td><?= $client['created_at'] ?></td>
            <td>
                <a href="?delete=<?= $client['id'] ?>" class="delete"
                   onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">Verwijderen</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>