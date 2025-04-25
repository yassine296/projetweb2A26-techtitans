<?php
require_once 'connexion.php'; // adapte le chemin

try {
    $db = config::getConnexion();
    echo "✅ Connexion à la base de données réussie !";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
?>