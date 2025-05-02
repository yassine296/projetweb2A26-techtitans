<?php
// Fonction pour obtenir la connexion à la base de données
function getConnexion() {
    try {
        // Remplace ces informations par celles de ta base de données
        $host = 'localhost';
        $dbname = 'hezni';  // Nom de ta base de données
        $username = 'root';   // Ton nom d'utilisateur
        $password = '';    // Ton mot de passe

        // Créer une nouvelle instance de PDO pour se connecter à la base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Activer la gestion des erreurs
        return $pdo;  // Retourne l'objet PDO pour l'utiliser ailleurs
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());  // Affiche un message d'erreur si la connexion échoue
    }
}
?>
