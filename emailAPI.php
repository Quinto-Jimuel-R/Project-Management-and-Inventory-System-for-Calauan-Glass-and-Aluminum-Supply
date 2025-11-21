<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    require './database.php';

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER') ?: '72d53b004@smtp-brevo.com';
    $mail->Password = getenv('SMTP_PASS') ?: 'QGIKpbU5yYZvj7da';
    $mail->Port = intval(getenv('SMTP_PORT') ?: 587);
    $mail->SMTPSecure = getenv('SMTP_SECURE') ?: '';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $from_email = getenv('FROM_EMAIL') ?: 'calauanglass@gmail.com';
    $from_name = getenv('FROM_NAME') ?: 'Calauan Glass';

    $mail->Subject = "OTP";
    $mail->Body = "Request Verification Code: $otp";
    $mail->setFrom($from_email, $from_name);
    $mail->addAddress($email, 'Recipient Name: ');

    $mail->isHTML(true);

    $mail->send();
?>