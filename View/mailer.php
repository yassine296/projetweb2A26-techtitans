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
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'zariatyassine1@gmail.com'; // Remplacez par vos identifiants
        $mail->Password = 'ynvp ocpr pkry kedf'; 
        $mail->Port = 587;
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