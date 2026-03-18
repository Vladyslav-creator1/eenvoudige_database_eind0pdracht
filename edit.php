<?php
global $pdo;
require 'db.php';

// Controleer of er een ID is meegegeven
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Haal de gegevens van de specifieke klant op
$stmt = $pdo->prepare("SELECT * FROM usersdb WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

// Als klant niet bestaat, terug naar index
if (!$client) {
    header("Location: index.php");
    exit;
}

// Verwerk het update-formulier
if (isset($_POST['update'])) {
    $naam = $_POST['naam'];
    $email = $_POST['email'];
    $adres = $_POST['adres'];
    $woonplaats = $_POST['woonplaats'];

    // Update query met prepared statement
    $stmt = $pdo->prepare("UPDATE usersdb SET naam = ?, email = ?, adres = ?, woonplaats = ? WHERE id = ?");
    $stmt->execute([$naam, $email, $adres, $woonplaats, $id]);

    // Terug naar overzicht na succesvol bijwerken
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="edit.css">
    <title>Klant bewerken</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .edit-form {
            max-width: 500px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .edit-form input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .edit-form button {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .cancel {
            background: #666;
            margin-left: 10px;
        }
        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
<h1>Klant bewerken</h1>

<div class="edit-form">
    <form method="POST">
        <label>Naam:</label>
        <input type="text" name="naam" value="<?= htmlspecialchars($client['naam']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

        <label>Adres:</label>
        <input type="text" name="adres" value="<?= htmlspecialchars($client['adres']) ?>" required>

        <label>Woonplaats:</label>
        <input type="text" name="woonplaats" value="<?= htmlspecialchars($client['woonplaats']) ?>" required>

        <button type="submit" name="update">Opslaan</button>
        <a href="index.php"><button type="button" class="cancel">Annuleren</button></a>
    </form>
</div>
</body>
</html>