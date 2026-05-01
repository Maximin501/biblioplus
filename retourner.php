<?php 
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;

// Récupérer l'emprunt
$emprunt = $pdo->prepare("SELECT * FROM emprunts WHERE id = ? AND date_retour IS NULL");
$emprunt->execute([$id]);
$emprunt = $emprunt->fetch();

if ($emprunt) {
    // Marquer le retour
    $pdo->prepare("UPDATE emprunts SET date_retour = CURDATE() WHERE id = ?")->execute([$id]);
    // Remettre le livre disponible
    $pdo->prepare("UPDATE livres SET disponible = TRUE WHERE id = ?")->execute([$emprunt['livre_id']]);
}

header('Location: emprunts.php');
exit;
?>