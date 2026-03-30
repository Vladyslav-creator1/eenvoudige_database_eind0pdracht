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

// Verwerk het update-formulier+
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