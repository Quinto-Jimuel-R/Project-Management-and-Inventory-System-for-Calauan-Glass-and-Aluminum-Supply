<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    require './database.php';

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp-relay.brevo.com";
    $mail->SMTPAuth = true;
    $mail->Username   = '72d53b004@smtp-brevo.com';
    $mail->Password   = 'QGIKpbU5yYZvj7da';
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->Subject = "OTP";
    $mail->Body = "Request Verification Code: $otp";
    $mail->setFrom('calauanglass@gmail.com', 'Calauan Glass');
    $mail->addAddress($email, 'Recipient Name: '); 

    $mail->isHTML(true);

    $mail->send();
?>