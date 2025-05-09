<?php
require_once '../model/message.php';
require_once '../config/connexion.php';

// Paramètres par défaut (à adapter selon votre logique)
$id_utilisateur_actuel = 1; // ID de l'utilisateur connecté
$id_destinataire = 2;       // ID du destinataire par défaut

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contenu'])) {
        // Envoi d'un nouveau message
        try {
            $message = new message(
                $_POST['contenu'],
                $id_utilisateur_actuel,
                $id_destinataire
            );
            $message->ajouter();
        } catch (Exception $e) {
            $errorMessage = "Erreur: " . $e->getMessage();
        }
    } 
    elseif (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'supprimer':
                message::supprimer($_POST['id_message']);
                break;
            case 'modifier':
                message::modifier(
                    $_POST['id_message'],
                    $_POST['nouveau_contenu']
                );
                break;
        }
    }
    // Redirection pour éviter le rechargement du formulaire
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Récupération des messages
$messages = message::afficher($id_utilisateur_actuel, $id_destinataire);

// Inclusion de la vue
include '../view/projet1.php';
?>