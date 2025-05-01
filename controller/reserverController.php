<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../model/AnnonceBagage.php';
require_once '../model/reserverModel.php';
require_once '../config/connexion.php';

// Initialisation
$annonceModel = new annonce_bagages();
$annonces = [];
$successMessage = '';
$errorMessage = '';

// Fonction pour ajouter une notification au conducteur
function addDriverNotification($annonceId, $placesReserved, $driverId = 1) {
    if (!isset($_SESSION['driver_notifications'][$driverId])) {
        $_SESSION['driver_notifications'][$driverId] = [];
    }
    
    $notification = [
        'annonce_id' => $annonceId,
        'message' => "Réservation de $placesReserved place(s) pour votre trajet",
        'date' => date('d/m/Y H:i'),
        'read' => false
    ];
    
    array_unshift($_SESSION['driver_notifications'][$driverId], $notification);
}

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

                // Envoyer notification au conducteur (ID = 1 temporairement)
                addDriverNotification($_POST['id_annonce'], $_POST['nb_places']);
                
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



// Traitement de la modification de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    try {

         // Récupérer l'ancienne réservation
         $oldReservation = reservation_bagage::getReservationById($_POST['reservation_id']);
        
         if ($oldReservation) {
             $difference = $_POST['nb_places'] - $oldReservation['nb_places'];
             $annonceModel->updatePlacesM($oldReservation['id_annonce'], -$difference);
         }

        if (reservation_bagage::update(
            $_POST['reservation_id'],
            $_POST['nb_places'],
            $_POST['type_bagage'],
            $_POST['prix_total']
        )) {
            $_SESSION['successMessage'] = "Réservation modifiée avec succès!";
        } else {
            $_SESSION['errorMessage'] = "Erreur lors de la modification de la réservation";
        }
        header("Location: ../view/historiqueReservations.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['errorMessage'] = "Erreur: ".$e->getMessage();
        header("Location: ../view/historiqueReservations.php");
        exit();
    }
}



// Traitement de la suppression de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    try {
        if (reservation_bagage::delete($_POST['reservation_id'])) {
            $_SESSION['successMessage'] = "Réservation supprimée avec succès!";
        } else {
            $_SESSION['errorMessage'] = "Erreur lors de la suppression de la réservation";
        }
        header("Location: ../view/historiqueReservations.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['errorMessage'] = "Erreur: ".$e->getMessage();
        header("Location: ../view/historiqueReservations.php");
        exit();
    }
}





// Récupérer les annonces
try {
    $annonces = $annonceModel->getAllBagages();
} catch (Exception $e) {
    $errorMessage = "Erreur de chargement: ".$e->getMessage();
}

require '../view/reservationBagages.php';
?>






