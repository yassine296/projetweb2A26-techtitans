<?php





// Gestion de la modification
if (isset($_POST['modifier'])) {
    $recharge = new recharge();
    $recharge->setIdr($_POST['idr']);
    $recharge->setStatut($_POST['statut']);
    
    if ($recharge->update()) {
        header("Location: rechargecontroller.php?success=1");
        exit();
    } else {
        header("Location: rechargecontroller.php?error=1");
        exit();
    }
}


require_once '../model/recharge.php';
require_once '../config/connexion.php';

if (isset($_POST['ajouter'])) {
    $recharge = new recharge(
        $_POST['idu'],
        $_POST['date'],
        $_POST['numcarte'],
        $_POST['montant'],
        
        $_POST['soldetotal'],
        $_POST['statut']

    );

    // Appel direct à la méthode create() de l'objet
    $recharge->create();
}

try {
    $annonceModel = new recharge();
    $annonces = $annonceModel->getAllrecharge();
} catch (Exception $e) {
    error_log('Erreur lors de la récupération des recharges: ' . $e->getMessage());
    $annonces = []; // Initialisation vide pour éviter des erreurs dans la vue
}

include __DIR__ . '/../view/soldeback.php';








if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['idr'])) {
    try {
        $annonceModel = new recharge();
        $success = $annonceModel->delete($_GET['idr']);
        
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


?>