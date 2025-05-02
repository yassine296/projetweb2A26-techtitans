<?php

include __DIR__ . '/../controller/TrajetCRUD.php';
include_once __DIR__ . '/../model/Trajets.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['IDT'])) {  
    $id = intval($_GET['IDT']); // ✅ on récupère et sécurise l'ID

    $TrajetController = new TrajetC();
    $TrajetController->supprimerTrajet($id);

    header("Location: Trajet.php");
    exit;
} else {
    echo "Erreur : IDT non spécifié ou invalide.";
}
?>
