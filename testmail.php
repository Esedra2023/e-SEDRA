<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';





//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtps.aruba.it'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = 'postmaster@itisvc.it'; //SMTP username
    $mail->Password = 'Vercelli_2022!'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('postmaster@itisvc.it', 'Notifiche e-Sedra');
    $mail->addAddress('profssapela@gmail.com', 'Barbara'); //Add a recipient
    $mail->addAddress('barbara.pela@itisvc.it'); //Name is optional
    $mail->addAddress('barbara.pela@scuola.istruzione.it', 'BP'); //Name is optional
    $mail->addReplyTo('dip_informatica@itisvc.it', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = 'Oggetto';
    $mail->Body = 'Corpo del messaggio <b>in neretto!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'il messaggio è stato inviato';
} catch (Exception $e) {
    echo "Il messaggio non può essere inviato. Mailer Error: {$mail->ErrorInfo}";
}
?>