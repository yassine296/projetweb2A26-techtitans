<?php
require_once __DIR__.'/../model/paiementmodel.php';
require_once __DIR__.'/../config/connexion.php';

class paiementcontroller {
    public static function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {
            $db = Database::getConnection();
            
            try {
                $db->beginTransaction();
                
                $dateExpiration = null;
                if (!empty($_POST['date_expiration'])) {
                    $parts = explode('/', $_POST['date_expiration']);
                    if (count($parts) === 2) {
                        $month = $parts[0];
                        $year = '20'.$parts[1];
                        $dateExpiration = $year.'-'.$month.'-01';
                    }
                }

                $paymentData = [
                    'id_utilisateur' => $_POST['id_utilisateur'],
                    'montant' => $_POST['montant'],
                    'date_paiement' => date('Y-m-d H:i:s'),
                    'type_paiement' => $_POST['type_paiement'],
                    'statut' => 'Réussi',
                    'nom_carte' => $_POST['nom_carte'] ?? null,
                    'numero_carte' => $_POST['numero_carte'] ?? null,
                    'date_expiration' => $dateExpiration,
                    'cvv' => $_POST['cvv'] ?? null
                ];
                
                if ($_POST['type_paiement'] === 'solde') {
                    $updateQuery = $db->prepare("
                        UPDATE recharge 
                        SET soldetotal = soldetotal - :montant 
                        WHERE idu = :user_id
                        ORDER BY idr DESC
                        LIMIT 1
                    ");
                    $updateQuery->execute([
                        ':montant' => $_POST['montant'],
                        ':user_id' => $_POST['id_utilisateur']
                    ]);
                }
                
                if (Payment::create($paymentData)) {
                    $db->commit();
                    header('Location: ../view/paiement.php?success=1');
                } else {
                    throw new Exception("Erreur création paiement");
                }
            } catch (Exception $e) {
                $db->rollBack();
                header('Location: ../view/paiement.php?error=1');
            }
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {
    paiementcontroller::process();
}
?>