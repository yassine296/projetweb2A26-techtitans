<?php
include_once __DIR__ . '/../connexion.php';  
require_once __DIR__ . '/../model/Trajets.php';  

class TrajetC
{
    public function ajouterTrajet($trajet)
    {
        $sql = "INSERT INTO trajet 
                VALUES (NULL, :V_DEP, :V_ARR, :DATE, :HEURE, :NB_PASS, :NB_PV, :NB_MV, :NB_GV, :PRIX)";
        $db = getConnexion();  
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'V_DEP' => $trajet->getV_DEP(),
                'V_ARR' => $trajet->getV_ARR(),
                'DATE' => $trajet->getDATE(),
                'HEURE' => $trajet->getHEURE(),
                'NB_PASS' => $trajet->getNB_PASS(),
                'NB_PV' => $trajet->getNB_PV(),
                'NB_MV' => $trajet->getNB_MV(),
                'NB_GV' => $trajet->getNB_GV(),
                'PRIX' => $trajet->getPRIX(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function modifierTrajet($trajet, $id)
    {
        try {
            $db = getConnexion(); 
            $query = $db->prepare(
                'UPDATE trajet SET
                    V_DEP = :V_DEP,
                    V_ARR = :V_ARR,
                    DATE = :DATE,
                    HEURE = :HEURE,
                    NB_PASS = :NB_PASS,
                    NB_PV = :NB_PV,
                    NB_MV = :NB_MV,
                    NB_GV = :NB_GV,
                    PRIX = :PRIX
                WHERE IDT = :id'
            );

            $query->execute([
                'id' => $id,
                'V_DEP' => $trajet->getV_DEP(),
                'V_ARR' => $trajet->getV_ARR(),
                'DATE' => $trajet->getDATE(),
                'HEURE' => $trajet->getHEURE(),
                'NB_PASS' => $trajet->getNB_PASS(),
                'NB_PV' => $trajet->getNB_PV(),
                'NB_MV' => $trajet->getNB_MV(),
                'NB_GV' => $trajet->getNB_GV(),
                'PRIX' => $trajet->getPRIX()
            ]);

            echo $query->rowCount() . " trajet(s) mis à jour avec succès.<br>";
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function consulterTrajet()
    {
        try {
            $db = getConnexion();
            $query = $db->prepare("SELECT * FROM trajet");
            $query->execute();

            $results = $query->fetchAll();  

            $trajets = [];
            foreach ($results as $result) {
                $trajet = new Trajets(
                    $result['IDT'],
                    $result['V_DEP'],
                    $result['V_ARR'],
                    $result['DATE'],
                    $result['HEURE'],
                    $result['NB_PASS'],
                    $result['NB_PV'],
                    $result['NB_MV'],
                    $result['NB_GV'],
                    $result['PRIX']
                );
                $trajets[] = $trajet;  
            }

            return $trajets;  
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function supprimerTrajet($id)
    {
        try {
            $db = getConnexion(); 
            $query = $db->prepare("DELETE FROM trajet WHERE IDT = :id");
            $query->execute(['id' => $id]);

            echo $query->rowCount() . " trajet supprimé avec succès.<br>";
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function getAllTrajets()
    {
        try {
            $pdo = getConnexion(); 
            $stmt = $pdo->prepare("SELECT * FROM trajet ORDER BY DATE ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des trajets : " . $e->getMessage());
        }
    }

    public function chercherTrajet($id)
    {
        try {
            $db = getConnexion(); 
            $query = $db->prepare("SELECT * FROM trajet WHERE IDT = :id");
            $query->execute(['id' => $id]);

            $result = $query->fetch(); 

            if ($result) {
                $trajet = new Trajets(
                    $result['IDT'],
                    $result['V_DEP'],
                    $result['V_ARR'],
                    $result['DATE'],
                    $result['HEURE'],
                    $result['NB_PASS'],
                    $result['NB_PV'],
                    $result['NB_MV'],
                    $result['NB_GV'],
                    $result['PRIX']
                );
                return $trajet;
            } else {
                echo "Aucun trajet trouvé avec l'ID : $id";
                return null;
            }
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
} // <--- Parenthèse fermante ajoutée ici pour fermer la classe
?>
