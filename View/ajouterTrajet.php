<?php
  include __DIR__ . '/../controller/TrajetCRUD.php';
  include_once __DIR__ . '/../model/Trajets.php';



  $message = ''; 
  $trajet = new Trajets(); 
  $listeTrajets = []; 
  $trajetController=new TrajetC();
  $listeTrajets = $trajetController->consulterTrajet();

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $depart = $_POST['depart'];
    $destination = $_POST['destination'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $places = intval($_POST['places']);
    $GV = intval($_POST['GV']);
    $MV = intval($_POST['MV']);
    $PV = intval($_POST['PV']);
    $prix = $_POST['prix'];

    $trajet->setV_DEP($depart);
    $trajet->setV_ARR($destination);
    $trajet->setDATE($date);
    $trajet->setHEURE($heure);
    $trajet->setNB_PASS($places);
    $trajet->setNB_PV($GV);
    $trajet->setNB_MV($MV);
    $trajet->setNB_GV($PV);
    $trajet-> setPRIX($prix);

    $trajetController = new TrajetC();
    $result = $trajetController->ajouterTrajet($trajet);

    if ($result) {
      $trajet = new Trajets(); // On vide les champs du formulaire
      header("Location: Trajet.php?success=1");
       exit();
    } else {
      $trajet = new Trajets(); // On vide les champs du formulaire
      $trajetController=new TrajetC();
      $listeTrajets = $trajetController->consulterTrajet();
      header("Location: Trajet.php?success=1");
      exit();
    }

    
  }

  
?>
