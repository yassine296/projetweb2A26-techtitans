<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../models/Reponse.php');

class ReponseController {
    // Ajouter une réponse
    public function addReponse($reponse) {
        $sql = "INSERT INTO reponses (id_reclamation, message, date_reponse, id_admin) 
                VALUES (:id_reclamation, :message, :date_reponse, :id_admin)";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute([
                'id_reclamation' => $reponse->getIdReclamation(),
                'message' => $reponse->getMessage(),
                'date_reponse' => $reponse->getDateReponse()->format('Y-m-d H:i:s'),
                'id_admin' => $reponse->getIdAdmin()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
    // Mettre à jour une réponse
    public function updateReponse($reponse, $id) {
        $sql = "UPDATE reponses SET 
                message = :message, 
                date_reponse = :date_reponse, 
                id_admin = :id_admin 
                WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute([
                'message' => $reponse->getMessage(),
                'date_reponse' => $reponse->getDateReponse()->format('Y-m-d H:i:s'),
                'id_admin' => $reponse->getIdAdmin(),
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
    // Supprimer une réponse
    public function deleteReponse($id) {
        $sql = "DELETE FROM reponses WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
    // Récupérer une réponse par ID
    public function getReponseById($id) {
        $sql = "SELECT * FROM reponses WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
            return $req->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }
    
    // Récupérer toutes les réponses pour une réclamation
    public function getReponsesByReclamationId($id_reclamation) {
        $sql = "SELECT * FROM reponses WHERE id_reclamation = :id_reclamation ORDER BY date_reponse ASC";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_reclamation', $id_reclamation);
        try {
            $req->execute();
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
    
    // Récupérer toutes les réclamations avec indication de réponse
    public function getReclamationsWithReponses() {
        $sql = "SELECT r.*, 
                (SELECT COUNT(*) FROM reponses WHERE id_reclamation = r.id) as has_response 
                FROM reclamations r 
                ORDER BY r.date_reclamation DESC";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
    
    // Vérifier si une réclamation a des réponses
    public function hasReponse($id_reclamation) {
        $sql = "SELECT COUNT(*) as count FROM reponses WHERE id_reclamation = :id_reclamation";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_reclamation', $id_reclamation);
        try {
            $req->execute();
            $result = $req->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
?>
