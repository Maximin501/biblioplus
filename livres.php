<?php 
require_once 'config.php';
requireLogin();

// Recherche
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ? ORDER BY titre");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM livres ORDER BY titre");
}
$livres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BiblioPlus - Livres</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <h1>📚 BiblioPlus</h1>
        <div>
            <a href="index.php">Accueil</a>
            <a href="livres.php">Livres</a>
            <a href="emprunts.php">Emprunts</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </nav>
    
    <main>
        <h2>Gestion des livres</h2>
        
        <!-- Recherche -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un livre..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Rechercher</button>
            <a href="ajouter_livre.php" class="btn-add">+ Ajouter un livre</a>
        </form>
        
        <!-- Tableau des livres -->
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Année</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($livres) === 0): ?>
                    <tr><td colspan="6">Aucun livre trouvé</td></tr>
                <?php endif; ?>
                
                <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= htmlspecialchars($livre['titre']) ?></td>
                    <td><?= htmlspecialchars($livre['auteur']) ?></td>
                    <td><?= htmlspecialchars($livre['categorie']) ?></td>
                    <td><?= $livre['annee'] ?></td>
                    <td>
                        <span class="badge <?= $livre['disponible'] ? 'green' : 'red' ?>">
                            <?= $livre['disponible'] ? 'Disponible' : 'Emprunté' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <?php if ($livre['disponible']): ?>
                            <a href="emprunter.php?id=<?= $livre['id'] ?>" class="btn-small green">Emprunter</a>
                        <?php endif; ?>
                        <a href="modifier_livre.php?id=<?= $livre['id'] ?>" class="btn-small">Modifier</a>
                        <a href="supprimer_livre.php?id=<?= $livre['id'] ?>" class="btn-small red" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>