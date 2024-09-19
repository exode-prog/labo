<?php
if (!defined('DB_HOST')) {
    define('DB_HOST', '185.98.131.160');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'amsic2326370');
}
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'amsic2326370');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'N@sry3221646');
}

// Connexion à la base de données
try {
    $connexion = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>