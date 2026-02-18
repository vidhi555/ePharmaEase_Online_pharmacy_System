<?php
    // $to = "pvidhi782@gmail.com";
    // $subject = "Test mail()";
    // $msg = "This is Testing Mail.";
    // mail($to ,$subject, $msg);

// trial Page
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pvidhi782@gmail.com';
    $mail->Password = 'ibcialcohyfmmvll';   //must write app password for security
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('pvidhi782@gmail.com', 'ePharmaEase');   //your email
    $mail->addAddress('vidhi8511021@gmail.com');    //Receiver email

    $mail->Subject = 'PHPMailer Test';
    $mail->Body    = 'PHPMailer installed successfully 🎉Send First Mail!';

    $mail->send();
    echo "Mail sent successfully!";
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
