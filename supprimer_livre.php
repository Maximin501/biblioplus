<?php 
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$pdo->prepare("DELETE FROM livres WHERE id = ?")->execute([$id]);
header('Location: livres.php');
exit;
?>