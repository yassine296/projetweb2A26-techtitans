<?php
require_once '../model/recharge.php';
require_once '../config/connexion.php';

// ID utilisateur fixe (à remplacer par l'ID de l'utilisateur connecté plus tard)
$idu = 1;
$historique = recharge::getHistorique($idu);
if (isset($_POST['ajouter'])) {
    // Calcul du bonus (10% pour recharges > 50 DT)
    $montant = $_POST['montant'];
    $bonus = ($montant > 50) ? $montant * 0.1 : 0;
    $montantTotal = $montant + $bonus;

    $recharge = new recharge(
        $idu,
        $_POST['date'],
        $_POST['numcarte'],
        $montantTotal, // Montant avec bonus
        0, // solde initial (sera calculé)
        'en attente', // statut fixe
        $_POST['temps']
    );

    $nouveauSolde = $recharge->create();
    
    if ($nouveauSolde !== false) {
        
        header("Location: ../view/Hezni.php?success=1&new_balance=".$nouveauSolde."&bonus=".$bonus."&montant=".$montant);
        exit();
    } else {
        header("Location: ../view/Hezni.php?error=1");
        exit();
    }
}

// Récupérer l'historique pour l'affichage
$historique = recharge::getHistorique($idu);
?>