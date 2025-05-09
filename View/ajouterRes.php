<?php
include __DIR__ . '/../controller/ResCRUD.php';
include_once __DIR__ . '/../model/Reservations.php';
include_once __DIR__ . '/../model/Trajets.php';
include __DIR__ . '/../controller/TrajetCRUD.php';
include __DIR__ . '/mailer.php'; // Inclusion du fichier d'envoi d'emails

$message = ''; 
$reservation = new Reservation(); 
$trajet = new Trajets();
$reservationController = new ReservationC();
$TrajetController = new TrajetC();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idt = $_POST['IDT'] ?? null;
    $places = intval($_POST['places']);
    $gv = intval($_POST['GV']);
    $mv = intval($_POST['MV']);
    $pv = intval($_POST['PV']);
    $passenger_email = $_POST['passenger_email'] ?? '';

    if ($idt !== null && filter_var($passenger_email, FILTER_VALIDATE_EMAIL)) {
        try {
            // 1. Traitement de la réservation
            $reservation->setIDT($idt);
            $reservation->setV_PASS($places);
            $reservation->setV_GV($gv);
            $reservation->setV_MV($mv);
            $reservation->setV_PV($pv);
            $reservation->setPassengerEmail($passenger_email);

            $trajet = $TrajetController->chercherTrajet($idt);
            $trajet->setNB_PASS($trajet->getNB_PASS() - $places);
            $trajet->setNB_GV($trajet->getNB_GV() - $gv);
            $trajet->setNB_MV($trajet->getNB_MV() - $mv);
            $trajet->setNB_PV($trajet->getNB_PV() - $pv);

            $TrajetController->modifierTrajet($trajet, $idt);
            $reservationController->ajouterReservation($reservation);

            // 2. Préparation du contenu des emails
            $passenger_content = "
                <h1 style='color: #e53935;'>CONFIRMATION DE RESERVATION</h1>
                <p>Bonjour,</p>
                <p>Votre réservation a été confirmée avec succès.</p>
                <h3>Détails du trajet :</h3>
                <ul>
                    <li><strong>Trajet :</strong> ".htmlspecialchars($trajet->getV_DEP())." → ".htmlspecialchars($trajet->getV_ARR())."</li>
                    <li><strong>Date :</strong> ".htmlspecialchars($trajet->getDATE())."</li>
                    <li><strong>Heure :</strong> ".htmlspecialchars($trajet->getHEURE())."</li>
                    <li><strong>Places :</strong> $places</li>
                    <li><strong>Prix total :</strong> ".($trajet->getPRIX() * $places)." DT</li>
                </ul>
                <p>Merci d'utiliser Hezni !</p>
            ";

            $driver_content = "
                <h1 style='color: #e53935;'>NOUVELLE RESERVATION</h1>
                <p>Bonjour,</p>
                <p>Vous avez une nouvelle réservation pour votre trajet.</p>
                <h3>Détails :</h3>
                <ul>
                    <li><strong>Trajet :</strong> ".htmlspecialchars($trajet->getV_DEP())." → ".htmlspecialchars($trajet->getV_ARR())."</li>
                    <li><strong>Passager :</strong> ".htmlspecialchars($passenger_email)."</li>
                    <li><strong>Places réservées :</strong> $places</li>
                    <li><strong>Bagages :</strong> GV:$gv | MV:$mv | PV:$pv</li>
                </ul>
            ";

            // 3. Envoi des emails
            // Email au passager
            sendReservationEmail(
                $passenger_email,
                "Confirmation de votre réservation Hezni",
                $passenger_content
            );

            // Email au conducteur (supposons que l'email est dans l'objet trajet)
            $driver_email = $trajet->getDriverEmail(); // À implémenter dans votre modèle
            if ($driver_email && filter_var($driver_email, FILTER_VALIDATE_EMAIL)) {
                sendReservationEmail(
                    $driver_email,
                    "Nouvelle réservation pour votre trajet",
                    $driver_content
                );
            }

            // 4. Redirection avec succès
            header("Location: Hezni-Réserver.php?success=1");
            exit();

        } catch (Exception $e) {
            // Journalisation de l'erreur
            error_log("Erreur lors de la réservation: " . $e->getMessage());
            
            // Redirection avec erreur
            header("Location: Hezni-Réserver.php?error=1&message=" . urlencode($e->getMessage()));
            exit();
        }
    } else {
        $message = "Données invalides. Veuillez vérifier votre email.";
        header("Location: Hezni-Réserver.php?error=1&message=" . urlencode($message));
        exit();
    }
} else {
    header("Location: Hezni-Réserver.php");
    exit();
}
?>