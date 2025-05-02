<?php
include_once __DIR__ . '/../connexion.php';  
require_once __DIR__ . '/../model/Reservations.php';  

class ReservationC
{
    // Ajouter une réservation
    public function ajouterReservation($reservation)
    {
        $sql = "INSERT INTO reservations 
                VALUES (NULL, :IDT, :V_PASS, :V_PV, :V_MV, :V_GV, :PRIX)";
        $db = getConnexion();  
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'IDT' => $reservation->getIDT(),
                'V_PASS' => $reservation->getV_PASS(),
                'V_PV' => $reservation->getV_PV(),
                'V_MV' => $reservation->getV_MV(),
                'V_GV' => $reservation->getV_GV(),
                'PRIX' => $reservation->getPRIX(),
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Modifier une réservation
    public function modifierReservation($reservation, $id)
    {
        try {
            $db = getConnexion(); 
            $query = $db->prepare(
                'UPDATE reservations SET
                    IDT = :IDT,
                    V_PASS = :V_PASS,
                    V_PV = :V_PV,
                    V_MV = :V_MV,
                    V_GV = :V_GV,
                    PRIX = :PRIX
                WHERE IDR = :id'
            );

            $query->execute([
                'id' => $id,
                'IDT' => $reservation->getIDT(),
                'V_PASS' => $reservation->getV_PASS(),
                'V_PV' => $reservation->getV_PV(),
                'V_MV' => $reservation->getV_MV(),
                'V_GV' => $reservation->getV_GV(),
                'PRIX' => $reservation->getPRIX()
            ]);

            echo $query->rowCount() . " réservation(s) mise(s) à jour avec succès.<br>";
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Consulter toutes les réservations
    public function consulterReservations()
    {
        try {
            $db = getConnexion();
            $query = $db->prepare("SELECT * FROM reservations");
            $query->execute();

            $results = $query->fetchAll();  

            $reservations = [];
            foreach ($results as $result) {
                $reservation = new Reservation(
                    $result['IDR'],
                    $result['IDT'],
                    $result['V_PASS'],
                    $result['V_PV'],
                    $result['V_MV'],
                    $result['V_GV'],
                    $result['PRIX']
                );
                $reservations[] = $reservation;  
            }

            return $reservations;  

        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Supprimer une réservation
    public function supprimerReservation($id)
    {
        try {
            $db = getConnexion();  
            $query = $db->prepare("DELETE FROM reservations WHERE IDR = :id");
            $query->execute(['id' => $id]);

            echo $query->rowCount() . " réservation supprimée avec succès.<br>";
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Consulter une réservation par ID
    public function consulterReservation($id)
    {
        try {
            $db = getConnexion();  
            $query = $db->prepare("SELECT * FROM reservations WHERE IDR = :id");
            $query->execute(['id' => $id]);

            $result = $query->fetch();  

            if ($result) {
                $reservation = new Reservation(
                    $result['IDR'],
                    $result['IDT'],
                    $result['V_PASS'],
                    $result['V_PV'],
                    $result['V_MV'],
                    $result['V_GV'],
                    $result['PRIX']
                );
                return $reservation;
            } else {
                echo "Aucune réservation trouvée avec l'ID : $id";
                return null;
            }

        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}
?>
