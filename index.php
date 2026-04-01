<?php
session_start();

// if not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: Inlogin.php");
    exit();
}
global $pdo;
require 'db.php';

// Process search
$searchCondition = "";
$searchParams = [];

if (isset($_GET['searchForm']) && !empty($_GET['searchName'])) {
    $searchName = trim($_GET['searchName']);
    $searchCondition = "WHERE naam LIKE :search OR email LIKE :search OR woonplaats LIKE :search";
    $searchParams[':search'] = "%$searchName%";
}

// Get all clients or search results
$sql = "SELECT * FROM usersdb $searchCondition ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($searchParams);
$clients = $stmt->fetchAll();

// add new clients
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

// delete client
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
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>CRM - Clients</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .search-result { margin-bottom: 15px; padding: 10px; background: #e3f2fd; border-radius: 5px; }
    </style>
</head>
<body class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>CRM - Clients</h1>
    <a id="logout" href="logout.php" class="btn btn-secondary btn-sm">Logout</a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title">Zoeken</h3>
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="searchName" class="form-control"
                       placeholder="Zoek op naam, email of woonplaats..."
                       value="<?= isset($_GET['searchName']) ? htmlspecialchars($_GET['searchName']) : '' ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" name="searchForm" class="btn btn-primary">Zoeken</button>
                <?php if (isset($_GET['searchForm'])): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Show search result info -->
<?php if (isset($_GET['searchForm']) && !empty($_GET['searchName'])): ?>
    <div class="search-result">
        <strong>Zoekresultaten voor: "<?= htmlspecialchars($_GET['searchName']) ?>"</strong>
        <span class="badge bg-info ms-2"><?= count($clients) ?> resultaten gevonden</span>
    </div>
<?php endif; ?>

<!-- Form to add new client -->
<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title">Nieuwe klant toevoegen</h3>
        <form method="POST" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="naam" class="form-control" placeholder="Naam" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="adres" class="form-control" placeholder="Adres" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="woonplaats" class="form-control" placeholder="Woonplaats" required>
            </div>
            <div class="col-12">
                <button type="submit" name="add" class="btn btn-success">Toevoegen</button>
            </div>
        </form>
    </div>
</div>

<!-- Clients table -->
<?php if (count($clients) > 0): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Email</th>
            <th>Adres</th>
            <th>Woonplaats</th>
            <th>Gemaakt op</th>
            <th>Actie</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $client['id'] ?></td>
                <td><?= htmlspecialchars($client['naam']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= htmlspecialchars($client['adres']) ?></td>
                <td><?= htmlspecialchars($client['woonplaats']) ?></td>
                <td><?= $client['created_at'] ?></td>
                <td>
                    <a href="Edit.php?id=<?= $client['id'] ?>" class="btn btn-primary btn-sm">Bewerken</a>
                    <a href="?delete=<?= $client['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">Verwijderen</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        <?= isset($_GET['searchForm']) ? 'Geen resultaten gevonden voor deze zoekopdracht.' : 'Geen clients gevonden. Voeg een nieuwe client toe!' ?>
    </div>
<?php endif; ?>
</body>
</html>