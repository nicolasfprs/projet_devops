<?php 
// Démarrer la session en début de fichier
session_start();

// Configuration sécurisée des cookies de session
ini_set('session.cookie_httponly', 1);
// Activer uniquement si HTTPS est disponible
// ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);

// Générer un jeton CSRF s'il n'existe pas déjà
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configurer les en-têtes de sécurité HTTP - version plus permissive pour les CDN
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data:;");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header_remove("X-Powered-By");

// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','notes_esigelec');

// Establish database connection using PDO.
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, 
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Enable error reporting
        )
    );
} catch (PDOException $e) {
    // Log error for administrators but show generic message to users
    error_log("Database connection error: " . $e->getMessage());
    exit("Erreur de connexion à la base de données. Veuillez contacter l'administrateur.");
}

// Fonction utilitaire pour vérifier le jeton CSRF
function verifyCsrfToken() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}
?>