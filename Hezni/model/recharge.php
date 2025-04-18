<?php
class recharge {
    private $idr;
    private $idu;
    private $date;
    private $numcarte;
    private $montant;
    private $soldetotal;
    private $statut;
    private $temps;


    // Constructeur
    public function __construct( $idu, $date, $numcarte, $montant, $soldetotal,$statut,$temps) {
       
        $this->idu = $idu;
        $this->date = $date;
        $this->numcarte = $numcarte;
        $this->montant = $montant;
        $this->soldetotal = $soldetotal;
        $this->statut = $statut;
        $this->temps = $temps;
    }

    // Getters
    public function getIdr() { return $this->idr; }
    public function getIdu() { return $this->idu; }
    public function getDate() { return $this->date; }
    public function getNumcarte() { return $this->numcarte; }
    public function getMontant() { return $this->montant; }
    public function getSoldetotal() { return $this->soldetotal; }
    public function getStatut() { return $this->statut; }
    public function getTemps() { return $this->temps; }


    // Setters
    public function setIdr($idr) { $this->idr = $idr; }
    public function setIdu($idu) { $this->idu = $idu; }
    public function setDate($date) { $this->date = $date; }
    public function setNumcarte($numcarte) { $this->numcarte = $numcarte; }
    public function setMontant($montant) { $this->montant = $montant; }
    public function setSoldetotal($soldetotal) { $this->soldetotal = $soldetotal; }
    public function setStatut($statut) { $this->statut = $statut; }
    public function setTemps($temps) { $this->temps = $temps; }


//ajouter dans le formulaire 
    public function create() {

        $sql = "INSERT INTO recharge ( idu, date, numcarte, montant, soldetotal,statut,temps)
                VALUES (:idu, :date, :numcarte, :montant, :soldetotal, :statut,:temps)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                
                'idu' => $this->getidu(),
                'date' => $this->getDate(),
                'numcarte' => $this->getNumcarte(),
                'montant' => $this->getMontant(),
                'soldetotal' => $this->getSoldeTotal(),
                'statut' => $this->getStatut(),
                'temps' => $this->getTemps()

            ]);


        return true;
            echo "✅ Recharge ajoutée avec succès !";
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
            return false;
        }
    }
  
    

}
?>