<?php 
require_once 'config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, isbn, categorie, annee) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['titre'],
        $_POST['auteur'],
        $_POST['isbn'],
        $_POST['categorie'],
        $_POST['annee']
    ]);
    header('Location: livres.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
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
        <h2>Ajouter un livre</h2>
        
        <form method="POST" class="form-livre">
            <label>Titre *</label>
            <input type="text" name="titre" required>
            
            <label>Auteur *</label>
            <input type="text" name="auteur" required>
            
            <label>ISBN</label>
            <input type="text" name="isbn">
            
            <label>Catégorie</label>
            <select name="categorie">
                <option>Roman</option>
                <option>Science</option>
                <option>Histoire</option>
                <option>Informatique</option>
                <option>Philosophie</option>
                <option>Autre</option>
            </select>
            
            <label>Année</label>
            <input type="number" name="annee" min="1900" max="2026">
            
            <button type="submit">Ajouter</button>
            <a href="livres.php" class="btn-cancel">Annuler</a>
        </form>
    </main>
</body>
</html>