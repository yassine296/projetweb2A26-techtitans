<?php
require_once __DIR__.'/../config/connexion.php';

class Payment {
    public static function create($data) {
        $db = Database::getConnection();
        
        $query = $db->prepare("
            INSERT INTO paiement (
                id_utilisateur, montant, date_paiement, 
                type_paiement, statut, nom_carte, 
                numero_carte, date_expiration, cvv
            ) VALUES (
                :id_utilisateur, :montant, :date_paiement,
                :type_paiement, :statut, :nom_carte,
                :numero_carte, :date_expiration, :cvv
            )
        ");
        
        return $query->execute([
            ':id_utilisateur' => $data['id_utilisateur'],
            ':montant' => $data['montant'],
            ':date_paiement' => $data['date_paiement'],
            ':type_paiement' => $data['type_paiement'],
            ':statut' => $data['statut'],
            ':nom_carte' => $data['nom_carte'],
            ':numero_carte' => $data['numero_carte'],
            ':date_expiration' => $data['date_expiration'],
            ':cvv' => $data['cvv']
        ]);
    }
    
    public static function getHistory($userId) {
        $db = Database::getConnection();
        $query = $db->prepare("SELECT * FROM paiement WHERE id_utilisateur = :user_id ORDER BY date_paiement DESC");
        $query->execute([':user_id' => $userId]);
        return $query->fetchAll();
    }
    
    public static function getBalance($userId) {
        $db = Database::getConnection();
        
        $query = $db->prepare("
            SELECT soldetotal AS solde
            FROM recharge
            WHERE idu = :user_id
            ORDER BY idr DESC
            LIMIT 1
        ");
        
        $query->execute([':user_id' => $userId]);
        $result = $query->fetch();
        
        return $result['solde'] ?? 0;
    }
}
?>