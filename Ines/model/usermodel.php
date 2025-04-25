<?php
require_once '../controller/userController.php'; // Inclure le contrôleur


class user
{
    private ?int $idU;
    private ?string $nom;
    private ?string $prenom;
    private ?string $email;
    private ?string $mdp;
    private ?string $role;

    public function __construct($i=null, $n=null, $p=null, $e=null, $m=null, $r=null)
    {
        $this->idU = $i;
        $this->nom = $n;
        $this->prenom = $p;
        $this->email = $e;
        $this->mdp = $m;
        $this->role = $r;
    }

    //getters
    public function getIdU()
    {
        return $this->idU;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getMdp()
    {
        return $this->mdp;
    }
    public function getRole()
    {
        return $this->role;
    }

    //setters
    public function setNom($nom)
    {
        $this->nom= $nom;
        return $this;
    }
    public function setPrenom($prenom)
    {
        $this->prenom= $prenom;
        return $this;
    }
    public function setEmail($email)
    {
        $this->email= $email;
        return $this;
    }
    public function setMdp($mdp)
    {
        $this->mdp= $mdp;
        return $this;
    }
    public function setRole($role)
    {
        $this->role= $role;
        return $this;
    }

    //Ajouter Etdudiant/ Conducteur dans le formulaire 
    public function create() {
        $sql = "INSERT INTO user (nom, prenom, email, mdp, role)
                VALUES (:nom, :prenom, :email, :mdp, :role)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $this->getNom(),
                'prenom' => $this->getPrenom(),
                'email' => $this->getEmail(),
                'mdp' => $this->getMdp(),
                'role' => $this->getRole()
            ]);
            echo "✅ Inscription avec succès !";
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
        }
    }

    
    //Authentification

    public static function authentifier($email, $mdp)
    {
    // Connexion à la base de données
    $db = config::getConnexion();

    // Requête pour vérifier si l'email existe dans la base
    $sql = "SELECT * FROM user WHERE email = :email AND mdp = :mdp";
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute(['email' => $email, 'mdp' => $mdp]);

        // Si un résultat est trouvé, l'utilisateur est authentifié
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user; // L'utilisateur est trouvé dans la base
        } else {
            return false; // Aucun utilisateur trouvé
        }
    } catch (Exception $e) {
        echo 'Erreur: ' . $e->getMessage();
        return false;
    }
    }

    //Ajouter Admin dans le formulaire 
    public function createA() {
        $sql = "INSERT INTO user (nom, prenom, email, mdp, role)
                VALUES (:nom, :prenom, :email, :mdp, :role)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $this->getNom(),
                'prenom' => $this->getPrenom(),
                'email' => $this->getEmail(),
                'mdp' => $this->getMdp(),
                'role' => $this->getRole()
            ]);
            echo "✅ Inscription avec succès !";
        } catch (Exception $e) {
            echo '❌ Erreur : ' . $e->getMessage();
        }
    }

    //Affichage de tous les users
   
    public static function getAllUsers() {
        $db = config::getConnexion();  // Assure-toi que DB::getConnection() fonctionne et te donne une connexion
        $query = "SELECT idU, nom, prenom, email, mdp, role FROM user";  // SQL pour récupérer les utilisateurs
        $result = $db->query($query);  // Exécution de la requête

        // Vérifie si des utilisateurs ont été récupérés
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);  // Retourne les données sous forme de tableau associatif
        } else {
            return [];  // Si pas de résultats, retourne un tableau vide
        }
    }
    

    //Supprimer User
    public static function delete($idU){
    $sql = "DELETE FROM user WHERE idU = :idU";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['idU' => $idU]);
            return true;
        } catch (Exception $e) {
            error_log('Erreur dans delete: ' . $e->getMessage());
            return false;
        }
    }

    //Modifier
    // Méthode pour récupérer une annonce par son ID
public function getUserById($idU) {
    $sql = "SELECT * FROM user WHERE idU = :idU";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute(['idU' => $idU]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Erreur dans getUserById: ' . $e->getMessage());
        return false;
    }
}

// Méthode pour mettre à jour une annonce
public function update($idU, $nom, $prenom, $email, $mdp, $role) {
    $sql = "UPDATE user SET 
            nom = :nom,
            prenom = :prenom,
            email = :email,
            mdp = :mdp,
            role = :role,
            WHERE idU = :idU";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->execute([
            'idU' => $idU,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mdp' => $mdp,
            'role' => $role,
        ]);
        return true;
    } catch (Exception $e) {
        error_log('Erreur dans update: ' . $e->getMessage());
        return false;
    }
}

    
}