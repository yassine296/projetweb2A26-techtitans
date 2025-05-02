<?php
require_once '../config/connexion.php';
class paiement {
    private $id_paiement;
    private $id_utilisateur;
    private $montant;
    private $date_paiement;
    private $type_paiement;
    private $statut;
    private $nom_carte;
    private $numero_carte;
    private $date_expiration;
    private $cvv;
    

    public function __construct($id_utilisateur=null, $montant=null, $date_paiement=null, $type_paiement=null, $statut=null, $nom_carte=null, $numero_carte=null, $date_expiration=null, $cvv=null) {
        $this->id_utilisateur = $id_utilisateur;
        $this->montant = $montant;
        $this->date_paiement = $date_paiement;
        $this->type_paiement = $type_paiement;
        $this->statut = $statut;
        $this->nom_carte = $nom_carte;
        $this->numero_carte = $numero_carte;
        $this->date_expiration = $date_expiration;
        $this->cvv = $cvv;
    }

    // Getters
    public function getIdp() { return $this->id_paiement; }
    public function getIdu() { return $this->id_utilisateur; }
    public function getMontant() { return $this->montant; }
    public function getdatepaiement() { return $this->date_paiement; }
    public function gettypepaiement() { return $this->type_paiement; }
    public function getStatut() { return $this->statut; }
    public function getNomcarte() { return $this->nom_carte; }
    public function getNumcarte() { return $this->numero_carte; }
    public function getdateexpiration() { return $this->date_expiration; }
    public function getCvv() { return $this->cvv; }

    // Setters
    public function setIdp($id_paiement) { $this->id_paiement = $id_paiement; }
    public function setIdu($id_utilisateur) { $this->id_utilisateur = $id_utilisateur; }
    public function setMontant($montant) { $this->montant = $montant; }
    public function setdatepaiement($date_paiement) { $this->date_paiement = $date_paiement; }
    public function settypepaiement($type_paiement) { $this->type_paiement = $type_paiement; }
    public function setStatut($statut) { $this->statut = $statut; }
    public function setNomcarte($nom_carte) { $this->nom_carte = $nom_carte; }
    public function setNumcarte($numero_carte) { $this->numero_carte = $numero_carte; }
    public function setdateexpiration($date_expiration) { $this->date_expiration = $date_expiration; }
    public function setCvv($cvv) { $this->cvv = $cvv; }

    public function getAllpaiement() {
        $sql = "SELECT * FROM paiement";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur dans getAllpaiement: ' . $e->getMessage());
            return [];
        }
    }

    public function delete($id_paiement) {
        $sql = "DELETE FROM paiement WHERE id_paiement = :id_paiement";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            return $query->execute(['id_paiement' => $id_paiement]);
        } catch (Exception $e) {
            error_log('Erreur dans delete: ' . $e->getMessage());
            return false;
        }
    }

    public function modifier() {
        $sql = "UPDATE paiement SET 
                id_utilisateur = :id_utilisateur,
                montant = :montant,
                date_paiement = :date_paiement,
                type_paiement = :type_paiement,
                statut = :statut,
                nom_carte = :nom_carte,
                numero_carte = :numero_carte,
                date_expiration = :date_expiration,
                cvv = :cvv
                WHERE id_paiement = :id_paiement";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            return $query->execute([
                'id_paiement' => $this->id_paiement,
                'id_utilisateur' => $this->id_utilisateur,
                'montant' => $this->montant,
                'date_paiement' => $this->date_paiement,
                'type_paiement' => $this->type_paiement,
                'statut' => $this->statut,
                'nom_carte' => $this->nom_carte,
                'numero_carte' => $this->numero_carte,
                'date_expiration' => $this->date_expiration,
                'cvv' => $this->cvv
            ]);
        } catch (Exception $e) {
            error_log('Erreur dans update: ' . $e->getMessage());
            return false;
        }
    }
}
?>