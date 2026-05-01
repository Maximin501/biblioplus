<?php 
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$livre = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$livre->execute([$id]);
$livre = $livre->fetch();

if (!$livre) {
    header('Location: livres.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE livres SET titre=?, auteur=?, isbn=?, categorie=?, annee=? WHERE id=?");
    $stmt->execute([$_POST['titre'], $_POST['auteur'], $_POST['isbn'], $_POST['categorie'], $_POST['annee'], $id]);
    header('Location: livres.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un livre</title>
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
        <h2>Modifier un livre</h2>
        <form method="POST" class="form-livre">
            <label>Titre *</label>
            <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
            <label>Auteur *</label>
            <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
            <label>ISBN</label>
            <input type="text" name="isbn" value="<?= htmlspecialchars($livre['isbn']) ?>">
            <label>Catégorie</label>
            <select name="categorie">
                <?php foreach (['Roman','Science','Histoire','Informatique','Philosophie','Autre'] as $cat): ?>
                    <option <?= $livre['categorie'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
            <label>Année</label>
            <input type="number" name="annee" value="<?= $livre['annee'] ?>">
            <button type="submit">Enregistrer</button>
            <a href="livres.php" class="btn-cancel">Annuler</a>
        </form>
    </main>
</body>
</html>