<?php
class annonce_bagages {
    private $id;
    private $ville_depart;
    private $ville_arrive;
    private $date;
    private $heure;
    private $nb_place;
    private $commentaire;
    private $date_publication;
    private $prix;

    // Constructeur
    public function __construct($ville_depart=null, $ville_arrive=null, $date=null, $heure=null, $nb_place=null, $commentaire=null,$prix=null) {
        $this->ville_depart = $ville_depart;
        $this->ville_arrive = $ville_arrive;
        $this->date = $date;
        $this->heure = $heure;
        $this->nb_place = $nb_place;
        $this->commentaire = $commentaire;
        $this->prix = $prix;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getVilleDepart() { return $this->ville_depart; }
    public function getVilleArrive() { return $this->ville_arrive; }
    public function getDate() { return $this->date; }
    public function getHeure() { return $this->heure; }
    public function getNbPlace() { return $this->nb_place; }
    public function getCommentaire() { return $this->commentaire; }
    public function getDatePublication() { return $this->date_publication; }
    public function getPrix() { return $this->prix; }


    // Setters
    public function setVilleDepart($ville_depart) { $this->ville_depart = $ville_depart; }
    public function setVilleArrive($ville_arrive) { $this->ville_arrive = $ville_arrive; }
    public function setDate($date) { $this->date = $date; }
    public function setHeure($heure) { $this->heure = $heure; }
    public function setNbPlace($nb_place) { $this->nb_place = $nb_place; }
    public function setCommentaire($commentaire) { $this->commentaire = $commentaire; }
    public function setPrix($prix) { $this->prix = $prix; }


//ajouter dans le formulaire 
    public function create() {
        $sql = "INSERT INTO annonce_bagages (ville_depart, ville_arrive, date, heure, nb_place, commentaire,prix)
                VALUES (:ville_depart, :ville_arrive, :date, :heure, :nb_place, :commentaire, :prix)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'ville_depart' => $this->getVilleDepart(),
                'ville_arrive' => $this->getVilleArrive(),
                'date' => $this->getDate(),
                'heure' => $this->getHeure(),
                'nb_place' => $this->getNbPlace(),
                'commentaire' => $this->getCommentaire(),
                'prix' => $this->getPrix()

            ]);
            echo "✅ Annonce ajoutée avec succès !";
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
        }
    }

// Affichage 
  
    public function getAllBagages() {
        $sql = "SELECT * FROM annonce_bagages";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur dans getAllBagages: ' . $e->getMessage());
            return []; // Retourne un tableau vide en cas d'erreur
        }
    } 
    
//supprimer
public function delete($id) {
    $sql = "DELETE FROM annonce_bagages WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur dans delete: ' . $e->getMessage());
        return false;
    }
}

//modifier

// Méthode pour récupérer une annonce par son ID
public function getBagageById($id) {
    $sql = "SELECT * FROM annonce_bagages WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Erreur dans getBagageById: ' . $e->getMessage());
        return false;
    }
}

// Méthode pour mettre à jour une annonce
public function update($id, $ville_depart, $ville_arrive, $date, $heure, $nb_place, $commentaire, $prix) {
    $sql = "UPDATE annonce_bagages SET 
            ville_depart = :ville_depart,
            ville_arrive = :ville_arrive,
            date = :date,
            heure = :heure,
            nb_place = :nb_place,
            commentaire = :commentaire,
            prix = :prix
            WHERE id = :id";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'id' => $id,
            'ville_depart' => $ville_depart,
            'ville_arrive' => $ville_arrive,
            'date' => $date,
            'heure' => $heure,
            'nb_place' => $nb_place,
            'commentaire' => $commentaire,
            'prix' => $prix
        ]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur dans update: ' . $e->getMessage());
        return false;
    }
}

}
?>