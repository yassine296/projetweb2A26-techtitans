<?php
class reservation_bagage {
    private $id;
    private $id_annonce;
    private $date_reservation;
    private $nb_places;
    private $type_bagage;
    private $prix_total;

    // Constructeur
    public function __construct($id_annonce=null, $nb_places=null, $type_bagage=null, $prix_total=null) {
        $this->nb_places = $nb_places;
        $this->id_annonce = $id_annonce;
        $this->type_bagage = $type_bagage;
        $this->prix_total = $prix_total;
    }

    // Getters
    public function getIdR() { return $this->id; }
    public function getIdAnnonce() { return $this->id_annonce; }
    public function getDateReservation() { return $this->date_reservation; }
    public function getNbPlaces() { return $this->nb_places; }
    public function getBaggageType() { return $this->type_bagage; }
    public function getPrixTotal() { return $this->prix_total; }

    // Setters
    public function setIdAnnonce($id_annonce) { $this->id_annonce = $id_annonce; }
    public function setDateReservation($date_reservation) { $this->date_reservation = $date_reservation; }
    public function setNbPlaces($nb_places) { $this->nb_places = $nb_places; }
    public function setBaggageType($type_bagage) { $this->type_bagage = $type_bagage; }
    public function setPrixTotal($prix_total) { $this->prix_total = $prix_total; }

    public function create() {
        $sql = "INSERT INTO reservation_bagage (id_annonce, date_reservation, nb_places, type_bagage, prix_total) 
                VALUES (:id_annonce, NOW(), :nb_places, :type_bagage, :prix_total)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            return $query->execute([
                'id_annonce' => $this->getIdAnnonce(),
                'nb_places' => $this->getNbPlaces(),
                'type_bagage' => $this->getBaggageType(),
                'prix_total' => $this->getPrixTotal()
            ]);
        } catch (Exception $e) {
            error_log('Erreur dans create reservation: ' . $e->getMessage());
            return false;
        }
    }
    
    // Méthode pour vérifier la disponibilité
    public static function checkAvailability($id_annonce, $nb_places) {
        $sql = "SELECT nb_place FROM annonce_bagages WHERE id = :id_annonce";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_annonce' => $id_annonce]);
            $annonce = $query->fetch(PDO::FETCH_ASSOC);
            
            if ($annonce && $annonce['nb_place'] >= $nb_places) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log('Erreur dans checkAvailability: ' . $e->getMessage());
            return false;
        }
    }





    // Dans reserverModel.php, ajoutez cette méthode à la classe reservation_bagage
public static function getReservations() {
    $sql = "SELECT r.*, a.ville_depart, a.ville_arrive, a.date, a.heure 
            FROM reservation_bagage r
            JOIN annonce_bagages a ON r.id_annonce = a.id
            ORDER BY r.date_reservation DESC";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Erreur dans getReservations: ' . $e->getMessage());
        return [];
    }
}

}
?>