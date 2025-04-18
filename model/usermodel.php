<?php

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = new PDO('mysql:host=localhost;dbname=Hezni', 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function ajouterUtilisateur($nom, $prenom, $email, $mdp, $role) {
        $sql = "INSERT INTO User (Nom, Prenom, Email, Mdp, Role)
                VALUES (:Nom, :Prenom, :Email, :Mdp, :Role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':Nom', $nom);
        $stmt->bindParam(':Prenom', $prenom);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':Mdp', $mdp);
        $stmt->bindParam(':Role', $role);
        $stmt->execute();
    }
    public function getAllUsers() {
        $sql = "SELECT * FROM User";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function getUserById($id) {
    //     $sql = "SELECT * FROM user WHERE IdU = :IdU";
    //     $db = config::getConnexion(); // Connexion à la base de données via config

    //     try {
    //         $query = $db->prepare($sql);
    //         $query->execute(['IdU' => $id]); // Paramètre pour l'ID de l'utilisateur
    //         return $query->fetch(PDO::FETCH_ASSOC); // Retourner l'utilisateur
    //     } catch (Exception $e) {
    //         error_log('Erreur dans getUserById: ' . $e->getMessage());
    //         return false; // Retourner false en cas d'erreur
    //     }
    // }
}