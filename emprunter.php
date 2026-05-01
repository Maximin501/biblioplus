<?php 
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;

// Vérifier que le livre existe et est disponible
$livre = $pdo->prepare("SELECT * FROM livres WHERE id = ? AND disponible = TRUE");
$livre->execute([$id]);
$livre = $livre->fetch();

if (!$livre) {
    header('Location: livres.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Créer l'emprunt
    $stmt = $pdo->prepare("INSERT INTO emprunts (livre_id, emprunteur, date_emprunt) VALUES (?, ?, CURDATE())");
    $stmt->execute([$id, $_POST['emprunteur']]);
    
    // Marquer le livre comme indisponible
    $pdo->prepare("UPDATE livres SET disponible = FALSE WHERE id = ?")->execute([$id]);
    
    header('Location: emprunts.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter un livre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <h1>📚 BiblioPlus</h1>
        <div>
            <a href="index.php">Accueil</a>
            <a href="livres.php">Livres</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </nav>
    
    <main>
        <h2>Emprunter un livre</h2>
        
        <div class="book-info">
            <h3><?= htmlspecialchars($livre['titre']) ?></h3>
            <p>Auteur : <?= htmlspecialchars($livre['auteur']) ?></p>
            <p>ISBN : <?= htmlspecialchars($livre['isbn'] ?? 'N/A') ?></p>
        </div>
        
        <form method="POST" class="form-livre">
            <label>Nom de l'emprunteur *</label>
            <input type="text" name="emprunteur" required placeholder="Ex: Jean Dupont">
            <button type="submit">Confirmer l'emprunt</button>
            <a href="livres.php" class="btn-cancel">Annuler</a>
        </form>
    </main>
</body>
</html>