<?php
require_once '../model/recharge.php';
require_once '../config/connexion.php';

if (isset($_POST['ajouter'])) {
    

    $recharge = new recharge(
        $_POST['idu'],
        $_POST['date'],
        $_POST['numcarte'],
        $_POST['montant'],
        
        $_POST['soldetotal'],
        $_POST['statut'],
        $_POST['temps']

    );

    // Appel direct à la méthode create() de l'objet
    $recharge->create();
}






?>