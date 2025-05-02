<?php
require_once '../model/backpaiementmodel.php';
require_once '../config/connexion.php';

// Gestion de la suppression
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'supprimer' && isset($_GET['id_paiement'])) {
        $paiement = new paiement();
        if ($paiement->delete($_GET['id_paiement'])) {
            header("Location: ../view/backpaiement.php?success=suppression");
        } else {
            header("Location: ../view/backpaiement.php?error=suppression");
        }
        exit();
    }
}

// Gestion de la modification
if (isset($_POST['modifier'])) {
    $paiement = new paiement();
    $paiement->setIdp($_POST['id_paiement']);
    $paiement->setIdu($_POST['id_utilisateur']);
    $paiement->setMontant($_POST['montant']);
    $paiement->setdatepaiement($_POST['date_paiement']);
    $paiement->settypepaiement($_POST['type_paiement']);
    $paiement->setStatut($_POST['statut']);
    $paiement->setNomcarte($_POST['nom_carte']);
    $paiement->setNumcarte($_POST['numero_carte']);
    $paiement->setdateexpiration($_POST['date_expiration']);
    $paiement->setCvv($_POST['cvv']);
    
    if ($paiement->modifier()) {
        header("Location: ../view/backpaiement.php?success=modification");
    } else {
        header("Location: ../view/backpaiement.php?error=modification");
    }
    exit();
}

?>