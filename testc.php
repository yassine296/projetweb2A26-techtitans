<?php
require_once('connexion.php');

try {
    $stmt = $pdo->query("SELECT 1");
    echo "<h2 style='color: green;'>✅ Connexion réussie à la base de données !</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Erreur de connexion : " . $e->getMessage() . "</h2>";
}
