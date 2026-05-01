<?php
session_start();

// Configuration InfinityFree
$host = 'sql311.infinityfree.com';  // Remplacer XXX par le numéro fourni
$dbname = 'if0_41801021_XXX';  // Nom exact fourni par InfinityFree
$user = 'if0_41801021';  // Votre nom d'utilisateur MySQL
$pass = 'maximin475';  // Votre mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
?>