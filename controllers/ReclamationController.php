<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../models/Reclamation.php');

class ReclamationController
{
    // Récupérer toutes les réclamations
    public function listReclamations()
    {
        $sql = "SELECT * FROM reclamations";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Afficher une réclamation par ID
    public function showReclamation($id)
    {
        $sql = "SELECT * FROM reclamations WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
            return $req->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Récupérer les réclamations par ID utilisateur
    public function getReclamationsByUserId($id_utilisateur)
    {
        $sql = "SELECT * FROM reclamations WHERE id_utilisateur = :id_utilisateur";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_utilisateur', $id_utilisateur);
        try {
            $req->execute();
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Ajouter une réclamation
    public function addReclamation($reclamation)
    {
        $sql = "INSERT INTO reclamations (type_utilisateur, id_utilisateur, sujet, message, is_read) VALUES (:type_utilisateur, :id_utilisateur, :sujet, :message, FALSE)";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute([
                'type_utilisateur' => $reclamation->getTypeUtilisateur(),
                'id_utilisateur' => $reclamation->getIdUtilisateur(),
                'sujet' => $reclamation->getSujet(),
                'message' => $reclamation->getMessage()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // Mettre à jour une réclamation
    public function updateReclamation($reclamation, $id)
    {
        $sql = "UPDATE reclamations SET type_utilisateur = :type_utilisateur, id_utilisateur = :id_utilisateur, sujet = :sujet, message = :message WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute([
                'id' => $id,
                'type_utilisateur' => $reclamation->getTypeUtilisateur(),
                'id_utilisateur' => $reclamation->getIdUtilisateur(),
                'sujet' => $reclamation->getSujet(),
                'message' => $reclamation->getMessage()
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // Supprimer une réclamation
    public function deleteReclamation($id)
    {
        $sql = "DELETE FROM reclamations WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
            return false;
        }
    }

    // Méthode pour récupérer toutes les réclamations
    public function getAllReclamations() 
    {
        $sql = "SELECT * FROM reclamations";
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
    
    // NOUVELLES MÉTHODES POUR LES NOTIFICATIONS
    
    // Marquer une réclamation comme lue
    public function markAsRead($id) {
        $sql = "UPDATE reclamations SET is_read = TRUE WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        try {
            $query->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    
    // Obtenir le nombre de réclamations non lues
    public function getUnreadCount() {
        $sql = "SELECT COUNT(*) as count FROM reclamations WHERE is_read = FALSE";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return 0;
        }
    }
    
    // Récupérer toutes les réclamations non lues
    public function getUnreadReclamations() {
        $sql = "SELECT * FROM reclamations WHERE is_read = FALSE ORDER BY date_reclamation DESC";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }
    
    // Marquer toutes les réclamations comme lues
    public function markAllAsRead() {
        $sql = "UPDATE reclamations SET is_read = TRUE WHERE is_read = FALSE";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            return $query->execute();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
?>