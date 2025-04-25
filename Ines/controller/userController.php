<?php
require_once 'C:/xampp/htdocs/Ines/connexion.php';
require_once '../model/usermodel.php';

// Ajouter Etudiant/ Conducteur

if (isset($_POST['ajouter'])) {
    try {
        $user = new user(
            null,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['mdp'],
            $_POST['role']
        );
        $user->create();
        
        // Redirection pour éviter la resoumission
        header('Location: ../view/success.php');
        exit();
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}

//authentification

if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Créer une instance de l'entité user
    // $user = new User();

    // Appeler la méthode d'authentification
    $authenticatedUser = $user = user::authentifier($email, $mdp);

    // Si l'utilisateur est authentifié
    if ($authenticatedUser) {
        // Rediriger vers une autre page (par exemple, une interface utilisateur après connexion)
        // header('Location: ../view/interface_apres.php');
        // exit();
        session_start();

        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user_id'] = $authenticatedUser['idU']; // ID utilisateur
        $_SESSION['user_role'] = $authenticatedUser['role']; // Rôle de l'utilisateur (admin, étudiant, conducteur)

        // Rediriger vers la page appropriée en fonction du rôle de l'utilisateur
        if ($authenticatedUser['role'] == 'admin') {
            header('Location: ../view/back.php'); // Interface admin
            exit();
        } else {
            header('Location: ../view/interface_apres.php'); // Interface utilisateur (étudiant ou conducteur)
            exit();
        }
    } else {
        // Afficher un message d'erreur si les données sont incorrectes
        $errorMessage = "Mail et mot de passe non validés";
        echo $errorMessage;
    }
}

 // Ajouter Admin
if (isset($_POST['ajouter'])) {
    try {
        $user = new user(
            null,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['mdp'],
            "Admin"
        );
        $user->createA();
        
        // Redirection pour éviter la resoumission
        header('Location: ../view/success.php');
        exit();
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}

// Affichage des users
$users = user::getAllUsers();  // Cette variable contient les utilisateurs récupérés depuis la base de données

// Traitement de la suppression (si demandée)
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['idU'])) {
    try {
        $user = new user();
        $success = $user->delete($_GET['idU']);
        
        /*if ($success) {
            // Redirection pour éviter la resoumission du formulaire
            header('Location: BagageController.php');
            exit();
        } else {
            echo "❌ Erreur lors de la suppression";
        }*/
    } catch (Exception $e) {
        error_log('Erreur lors de la suppression: ' . $e->getMessage());
        echo "❌ Erreur lors de la suppression";
    }
}

//Modifier 
// Traitement de la modification
if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['idU'])) {
    try {
        $user = new user();
        $userAModifier = $user->getUserById($_GET['idU']);
    } catch (Exception $e) {
        error_log('Erreur lors de la récupération de l\'utilisateur à modifier: ' . $e->getMessage());
    }
}

// Traitement de la soumission du formulaire de modification
if (isset($_POST['modifier'])) {
    try {
        $user = new user();
        $success = $user->update(
            $_POST['idU'],
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['mdp'],
            $_POST['role']
        );
        
        if ($success) {
            header('Location: userController.php');
            exit();
        } else {
            echo "❌ Erreur lors de la modification";
        }
    } catch (Exception $e) {
        error_log('Erreur lors de la modification: ' . $e->getMessage());
        echo "❌ Erreur lors de la modification";
    }
}

?>