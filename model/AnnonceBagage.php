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
<<<<<<< HEAD
=======
    private $conducteur_id;
>>>>>>> bfbf316 (second commit)

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
<<<<<<< HEAD
=======
    public function getconducteur_id() { return $this->conducteur_id; }
>>>>>>> bfbf316 (second commit)


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
<<<<<<< HEAD
        $sql = "INSERT INTO annonce_bagages (ville_depart, ville_arrive, date, heure, nb_place, commentaire,prix)
                VALUES (:ville_depart, :ville_arrive, :date, :heure, :nb_place, :commentaire, :prix)";
=======
        $sql = "INSERT INTO annonce_bagages (ville_depart, ville_arrive, date, heure, nb_place, commentaire,prix, conducteur_id)
                VALUES (:ville_depart, :ville_arrive, :date, :heure, :nb_place, :commentaire, :prix, 1)";
>>>>>>> bfbf316 (second commit)
        
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
<<<<<<< HEAD
  
    public function getAllBagages() {
        $sql = "SELECT * FROM annonce_bagages";
=======
   
    public function getAllBagages() {
        $sql = "SELECT * FROM annonce_bagages WHERE nb_place > 0";
>>>>>>> bfbf316 (second commit)
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erreur dans getAllBagages: ' . $e->getMessage());
            return []; // Retourne un tableau vide en cas d'erreur
        }
<<<<<<< HEAD
    } 
=======
    }
>>>>>>> bfbf316 (second commit)
    
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

<<<<<<< HEAD
=======



public function updatePlaces($id, $places_reservees) {
    $sql = "UPDATE annonce_bagages SET nb_place = nb_place - :places_reservees WHERE id = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        return $query->execute([
            'id' => $id,
            'places_reservees' => $places_reservees
        ]);
    } catch (Exception $e) {
        error_log('Erreur dans updatePlaces: ' . $e->getMessage());
        return false;
    }
}
// AnnonceBagage.php
public function updatePlacesM($id_annonce, $difference) {
    $sql = "UPDATE annonce_bagages 
            SET nb_place = nb_place + :difference 
            WHERE id = :id_annonce";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'difference' => $difference,
            'id_annonce' => $id_annonce
        ]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur updatePlaces: ' . $e->getMessage());
        return false;
    }
}



>>>>>>> bfbf316 (second commit)
}
?>