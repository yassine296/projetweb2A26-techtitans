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

    public function __construct($idu, $date, $numcarte, $montant, $soldetotal, $statut, $temps) {
        $this->idu = $idu;
        $this->date = $date;
        $this->numcarte = $numcarte;
        $this->montant = $montant;
        $this->soldetotal = $soldetotal;
        $this->statut = $statut;
        $this->temps = $temps;
    }

    // Getters et Setters (inchangés)

    public function create() {
        $db = config::getConnexion();
        
        try {
            // Récupérer le dernier solde
            $sql = "SELECT soldetotal FROM recharge WHERE idu = :idu ORDER BY idr DESC LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(['idu' => $this->idu]);
            $dernierSolde = $query->fetchColumn();
            
            $soldeActuel = $dernierSolde ? $dernierSolde : 0;
            $nouveauSolde = $soldeActuel + $this->montant;
            $this->soldetotal = $nouveauSolde;
            
            // Insérer la nouvelle recharge
            $insertSql = "INSERT INTO recharge (idu, date, numcarte, montant, soldetotal, statut, temps)
                          VALUES (:idu, :date, :numcarte, :montant, :soldetotal, :statut, :temps)";
            $insertQuery = $db->prepare($insertSql);
            $insertQuery->execute([
                'idu' => $this->idu,
                'date' => $this->date,
                'numcarte' => $this->numcarte,
                'montant' => $this->montant,
                'soldetotal' => $nouveauSolde,
                'statut' => $this->statut,
                'temps' => $this->temps
            ]);
            
            return $nouveauSolde;
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public static function getHistorique($idu) {
        $db = config::getConnexion();
        try {
            $sql = "SELECT * FROM recharge WHERE idu = :idu ORDER BY date DESC, temps DESC LIMIT 5";
            $query = $db->prepare($sql);
            $query->execute(['idu' => $idu]);
            return $query->fetchAll();
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
            return [];
        }
    }
}
?>