<?php

    include __DIR__ . '/../controller/TrajetCRUD.php';
    include_once __DIR__ . '/../model/Trajets.php';

    // Vérification de la méthode GET et des paramètres
    if ($_SERVER["REQUEST_METHOD"] === "GET" && $_GET['id']) {
        
        // Récupération de l'ID et des autres paramètres
        $id = $_GET['id']; // L'ID du trajet à modifier
        $depart = $_GET['depart'];
        $destination = $_GET['destination'];
        $date = $_GET['date'];
        $heure = $_GET['heure'];
        $places = intval($_GET['places']);
        $GV = intval($_GET['GV']);
        $MV = intval($_GET['MV']);
        $PV = intval($_GET['PV']);
        $prix = $_GET['prix'];

        // Création d'une instance de l'objet Trajet
        $trajet = new Trajets();
        
        // Assigner les valeurs récupérées aux attributs de l'objet
        $trajet->setV_DEP($depart);
        $trajet->setV_ARR($destination);
        $trajet->setDATE($date);
        $trajet->setHEURE($heure);
        $trajet->setNB_PASS($places);
        $trajet->setNB_PV($PV);
        $trajet->setNB_MV($MV);
        $trajet->setNB_GV($GV);
        $trajet->setPRIX($prix);
        
        // Appel du contrôleur pour modifier le trajet
        $TrajetController = new TrajetC();
        $TrajetController->modifierTrajet($trajet, $id);

        // Redirection après modification
        header("Location: Trajet.php");
        exit;

    } else {
        echo "Erreur : Tous les champs sont obligatoires ou requête invalide.";
    }
?>
