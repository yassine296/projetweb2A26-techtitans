<?php
require_once '../model/AnnonceBagage.php';
require_once '../config/connexion.php';

// Traitement du formulaire (si soumis)
if (isset($_POST['ajouter'])) {
    try {
        $bagage = new annonce_bagages(
            $_POST['ville_depart'],
            $_POST['ville_arrive'],
            $_POST['date'],
            $_POST['heure'],
            $_POST['nb_place'],
            $_POST['commentaire'],
            $_POST['prix']
        );
        $bagage->create();
        
        // Redirection pour éviter la resoumission
        header('Location: BagageController.php');
        exit();
    } catch (Exception $e) {
        $errorMessage = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}





// Traitement de la suppression (si demandée)
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    try {
        $annonceModel = new annonce_bagages();
        $success = $annonceModel->delete($_GET['id']);
        
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
// Traitement de la modification
if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['id'])) {
    try {
        $annonceModel = new annonce_bagages();
        $annonceAModifier = $annonceModel->getBagageById($_GET['id']);
    } catch (Exception $e) {
        error_log('Erreur lors de la récupération de l\'annonce à modifier: ' . $e->getMessage());
    }
}

// Traitement de la soumission du formulaire de modification
if (isset($_POST['modifier'])) {
    try {
        $annonceModel = new annonce_bagages();
        $success = $annonceModel->update(
            $_POST['id'],
            $_POST['ville_depart'],
            $_POST['ville_arrive'],
            $_POST['date'],
            $_POST['heure'],
            $_POST['nb_place'],
            $_POST['commentaire'],
            $_POST['prix']
        );
        
        if ($success) {
            header('Location: BagageController.php');
            exit();
        } else {
            echo "❌ Erreur lors de la modification";
        }
    } catch (Exception $e) {
        error_log('Erreur lors de la modification: ' . $e->getMessage());
        echo "❌ Erreur lors de la modification";
    }
}

// Chargement des annonces (TOUJOURS exécuté, même sans formulaire)
try {
    $annonceModel = new annonce_bagages();
    $annonces = $annonceModel->getAllBagages();
} catch (Exception $e) {
    error_log('Erreur lors de la récupération des annonces: ' . $e->getMessage());
    $annonces = []; // Initialisation vide pour éviter des erreurs dans la vue
}

include __DIR__ . '/../view/AjouterBagage.php';
?>
