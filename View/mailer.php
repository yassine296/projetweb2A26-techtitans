<?php
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendReservationEmail($to, $subject, $content) {
    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP pour Mailtrap
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'a04d74824d17b1'; // Remplacez par vos identifiants
        $mail->Password = '23e567e2fa49c0'; 
        $mail->Port = 2525;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPDebug = 0;
        

        // Expéditeur
        $mail->setFrom('reservations@hezni.tn', 'Service Réservation Hezni');
        $mail->addAddress($to);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->AltBody = strip_tags($content);

        if(!$mail->send()) {
            throw new Exception("Erreur SMTP: ".$mail->ErrorInfo);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Erreur mail: ".$e->getMessage());
        throw new Exception("Échec d'envoi: ".$e->getMessage());
    }
}
?>