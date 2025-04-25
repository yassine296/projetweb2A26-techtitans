<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../model/AnnonceBagage.php';
require_once '../model/reserverModel.php';
require_once '../config/connexion.php';

// Initialisation
$annonceModel = new annonce_bagages();
$annonces = [];
$successMessage = '';
$errorMessage = '';

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    try {
        $reservation = new reservation_bagage(
            $_POST['id_annonce'],
            $_POST['nb_places'],
            $_POST['type_bagage'],
            $_POST['prix_total']
        );
        
        if (reservation_bagage::checkAvailability($_POST['id_annonce'], $_POST['nb_places'])) {
            if ($reservation->create()) {
                $annonceModel->updatePlaces($_POST['id_annonce'], $_POST['nb_places']);
                $_SESSION['successMessage'] = "Réservation effectuée avec succès! Prix total: ".$_POST['prix_total']." DT";
                
                // Redirection pour éviter le re-soumission
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    } catch (Exception $e) {
        $_SESSION['errorMessage'] = "Erreur: ".$e->getMessage();
    }
}

// Récupération des messages de session
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}

if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']);
}

// Récupérer les annonces
try {
    $annonces = $annonceModel->getAllBagages();
} catch (Exception $e) {
    $errorMessage = "Erreur de chargement: ".$e->getMessage();
}





require '../view/reservationBagages.php';
?>






