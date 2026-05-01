<?php
// Génère le hash correct pour votre version de PHP
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe : admin123<br>";
echo "Hash généré : " . $hash . "<br>";
echo "Ce hash est compatible avec votre PHP " . phpversion();
?>