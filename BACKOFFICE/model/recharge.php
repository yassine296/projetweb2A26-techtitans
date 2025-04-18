<?php
class recharge {
    private $idr;
    private $idu;
    private $date;
    private $montant;
    private $numcarte;
    
    private $soldetotal;
    private $statut;


    // Constructeur
    public function __construct( $idu=null, $date=null, $numcarte=null, $montant=null, $soldetotal=null,$statut=null) {
        $this->idu = $idu;
        $this->date = $date;
        $this->numcarte = $numcarte;
        $this->montant = $montant;
        $this->soldetotal = $soldetotal;
        $this->statut = $statut;
    }

    // Getters
    public function getIdr() { return $this->idr; }
    public function getIdu() { return $this->idu; }
    public function getDate() { return $this->date; }
    public function getNumcarte() { return $this->numcarte; }
    public function getMontant() { return $this->montant; }
    public function getSoldetotal() { return $this->soldetotal; }
    public function getStatut() { return $this->statut; }


    // Setters
    public function setIdr($idr) { $this->idr = $idr; }
    public function setIdu($idu) { $this->idu = $idu; }
    public function setDate($date) { $this->date = $date; }
    public function setNumcarte($numcarte) { $this->numcarte = $numcarte; }
    public function setMontant($montant) { $this->montant = $montant; }
    public function setSoldetotal($soldetotal) { $this->soldetotal = $soldetotal; }
    public function setStatut($statut) { $this->statut = $statut; }


//ajouter dans le formulaire 
    public function create() {
        $sql = "INSERT INTO recharge ( idu, date, numcarte, montant, soldetotal,statut)
                VALUES (:idu, :date, :numcarte, :montant, :soldetotal, :statut)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'idu' => $this->getidu(),
                'date' => $this->getDate(),
                'numcarte' => $this->getNumcarte(),
                'montant' => $this->getMontant(),
                'soldetotal' => $this->getSoldeTotal(),
                'statut' => $this->getStatut()

            ]);
            echo "✅ Recharge ajoutée avec succès !";
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
        }
    }
  
   public function getAllrecharge() {
        $sql = "SELECT * FROM recharge";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur dans getAllrecharge: ' . $e->getMessage());
            return []; // Retourne un tableau vide en cas d'erreur
        }
    }


    public function delete($idr) {
    $sql = "DELETE FROM recharge WHERE idr = :idr";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['idr' => $idr]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur dans delete: ' . $e->getMessage());
        return false;
    }
}






public function modifier() {
    $sql = "UPDATE recharge SET 
            idu = :idu,
            date = :date,
            numcarte = :numcarte,
            montant = :montant,
            soldetotal = :soldetotal,
            statut = :statut
            WHERE idr = :idr";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $success = $query->execute([
            'idr' => $this->idr,
            'idu' => $this->idu,
            'date' => $this->date,
            'numcarte' => $this->numcarte,
            'montant' => $this->montant,
            'soldetotal' => $this->soldetotal,
            'statut' => $this->statut
        ]);
        
        if ($success) {
            return true;
        } else {
            error_log('Erreur lors de la mise à jour: aucune ligne affectée');
            return false;
        }
    } catch (Exception $e) {
        error_log('Erreur dans update: ' . $e->getMessage());
        return false;
    }
}

// Ajoutez aussi cette méthode pour récupérer une recharge par son ID
public static function getById($idr) {
    $sql = "SELECT * FROM recharge WHERE idr = :idr";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['idr' => $idr]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Erreur dans getById: ' . $e->getMessage());
        return false;
    }
}



}
?>