<?php
class message {
    private $id_message;
    private $contenu;
    private $date_envoie;
    private $id_expediteur;
    private $id_destinataire;

    public function __construct($contenu = null, $id_expediteur = null, $id_destinataire = null) {
        $this->contenu = $contenu;
        $this->id_expediteur = $id_expediteur;
        $this->id_destinataire = $id_destinataire;
    }

    // Getters
    public function getIdMessage() { return $this->id_message; }
    public function getContenu() { return $this->contenu; }
    public function getDateEnvoie() { return $this->date_envoie; }
    public function getIdExpediteur() { return $this->id_expediteur; }
    public function getIdDestinataire() { return $this->id_destinataire; }

    // Setters
    public function setIdMessage($id_message) { $this->id_message = $id_message; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
    public function setDateEnvoie($date_envoie) { $this->date_envoie = $date_envoie; }
    public function setIdExpediteur($id_expediteur) { $this->id_expediteur = $id_expediteur; }
    public function setIdDestinataire($id_destinataire) { $this->id_destinataire = $id_destinataire; }

    // CRUD Operations
    public function ajouter() {
        $db = config::getConnexion();
        try {
            // Commencer une transaction
            $db->beginTransaction();

            // Insertion dans la table expediteur
            $sql = "INSERT INTO expediteur (contenu, id_expediteur, id_destinataire) 
                    VALUES (:contenu, :id_expediteur, :id_destinataire)";
            $query = $db->prepare($sql);
            $query->execute([
                'contenu' => $this->contenu,
                'id_expediteur' => $this->id_expediteur,
                'id_destinataire' => $this->id_destinataire
            ]);
            $id_message = $db->lastInsertId();

            // Insertion dans la table destinataire
            $sql = "INSERT INTO destinataire (contenu, id_expediteur, id_destinataire, id_message) 
                    VALUES (:contenu, :id_expediteur, :id_destinataire, :id_message)";
            $query = $db->prepare($sql);
            $query->execute([
                'contenu' => $this->contenu,
                'id_expediteur' => $this->id_expediteur,
                'id_destinataire' => $this->id_destinataire,
                'id_message' => $id_message
            ]);

            // Valider la transaction
            $db->commit();
            return $id_message;

        } catch (Exception $e) {
            // Annuler en cas d'erreur
            $db->rollBack();
            echo '❌ Erreur lors de l\'ajout : ' . $e->getMessage();
            return false;
        }
    }

    public static function afficher($id_expediteur, $id_destinataire) {
        $db = config::getConnexion();
        try {
            $sql = "SELECT e.id_message, e.contenu, e.date_envoie, e.id_expediteur, e.id_destinataire, 'expediteur' as source 
                    FROM expediteur e
                    WHERE (e.id_expediteur = :id_exp AND e.id_destinataire = :id_dest)
                    UNION
                    SELECT d.id_message, d.contenu, d.date_envoie, d.id_expediteur, d.id_destinataire, 'destinataire' as source 
                    FROM destinataire d
                    WHERE (d.id_expediteur = :id_exp AND d.id_destinataire = :id_dest)
                    ORDER BY date_envoie ASC";
            
            $query = $db->prepare($sql);
            $query->execute([
                'id_exp' => $id_expediteur,
                'id_dest' => $id_destinataire
            ]);
            return $query->fetchAll();
        } catch (Exception $e) {
            echo '❌ Erreur lors de la récupération : ' . $e->getMessage();
            return [];
        }
    }

    public static function supprimer($id_message) {
        $db = config::getConnexion();
        try {
            $db->beginTransaction();

            $sql = "DELETE FROM destinataire WHERE id_message = :id";
            $query = $db->prepare($sql);
            $query->execute(['id' => $id_message]);

            $sql = "DELETE FROM expediteur WHERE id_message = :id";
            $query = $db->prepare($sql);
            $query->execute(['id' => $id_message]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            echo '❌ Erreur lors de la suppression : ' . $e->getMessage();
            return false;
        }
    }

    public static function modifier($id_message, $nouveau_contenu) {
        $db = config::getConnexion();
        try {
            $db->beginTransaction();

            $sql = "UPDATE expediteur SET contenu = :contenu WHERE id_message = :id";
            $query = $db->prepare($sql);
            $query->execute(['contenu' => $nouveau_contenu, 'id' => $id_message]);

            $sql = "UPDATE destinataire SET contenu = :contenu WHERE id_message = :id";
            $query = $db->prepare($sql);
            $query->execute(['contenu' => $nouveau_contenu, 'id' => $id_message]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            echo '❌ Erreur lors de la modification : ' . $e->getMessage();
            return false;
        }
    }
    
}
?>