<?php 
require_once 'config.php';
requireLogin();

// Statistiques
$totalLivres = $pdo->query("SELECT COUNT(*) FROM livres")->fetchColumn();
$livresDisponibles = $pdo->query("SELECT COUNT(*) FROM livres WHERE disponible = TRUE")->fetchColumn();
$empruntsEnCours = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BiblioPlus - Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <h1>📚 BiblioPlus</h1>
        <div>
            <a href="index.php">Accueil</a>
            <a href="livres.php">Livres</a>
            <a href="emprunts.php">Emprunts</a>
            <a href="logout.php">Déconnexion (<?= $_SESSION['username'] ?>)</a>
        </div>
    </nav>
    
    <main>
        <h2>Tableau de bord</h2>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?= $totalLivres ?></h3>
                <p>Livres au total</p>
            </div>
            <div class="stat-card green">
                <h3><?= $livresDisponibles ?></h3>
                <p>Livres disponibles</p>
            </div>
            <div class="stat-card orange">
                <h3><?= $empruntsEnCours ?></h3>
                <p>Emprunts en cours</p>
            </div>
        </div>
    </main>
</body>
</html>