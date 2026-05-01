<?php 
require_once 'config.php';
requireLogin();

$stmt = $pdo->query("
    SELECT e.*, l.titre, l.auteur 
    FROM emprunts e 
    JOIN livres l ON e.livre_id = l.id 
    WHERE e.date_retour IS NULL 
    ORDER BY e.date_emprunt DESC
");
$emprunts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BiblioPlus - Emprunts</title>
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
        <h2>Emprunts en cours</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Emprunteur</th>
                    <th>Date emprunt</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($emprunts) === 0): ?>
                    <tr><td colspan="4">Aucun emprunt en cours</td></tr>
                <?php endif; ?>
                
                <?php foreach ($emprunts as $emp): ?>
                <tr>
                    <td><?= htmlspecialchars($emp['titre']) ?> - <?= htmlspecialchars($emp['auteur']) ?></td>
                    <td><?= htmlspecialchars($emp['emprunteur']) ?></td>
                    <td><?= $emp['date_emprunt'] ?></td>
                    <td>
                        <a href="retourner.php?id=<?= $emp['id'] ?>" class="btn-small green">Retourner</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>