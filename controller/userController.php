<?php

require_once '../model/usermodel.php';

class UserController {
    public function inscrireUtilisateur() {
        $nom = $_POST['Nom'];
        $prenom = $_POST['Prenom'];
        $email = $_POST['Email'];
        $mdp = password_hash($_POST['Mdp'], PASSWORD_DEFAULT); // Sécurisation
        $role = 'Etudiant'; // ou autre valeur par défaut

        $model = new UserModel();
        $model->ajouterUtilisateur($nom, $prenom, $email, $mdp, $role);

        // Redirection ou message
        echo "Inscription réussie !";
    }
    public function getUtilisateurs() {
        $model = new UserModel();
        return $model->getAllUsers();
    }
// Traitement de la modification
public function modifierUtilisateur() {
    if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['IdU'])) {
        try {
            $userModel = new UserModel();  // Création de l'objet UserModel
            $utilisateurAModifier = $userModel->getUserById($_GET['IdU']);  // Appel de la méthode getUserById
            return $utilisateurAModifier;  // Retourne l'utilisateur récupéré
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération de l\'utilisateur à modifier: ' . $e->getMessage());
            return null;  // Retourne null en cas d'erreur
        }
    }
    return null;  // Retourne null si les paramètres ne sont pas définis
}

}