<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

define('DEV_MODE', false);

function sendOtpEmail($email, $otpCode) {
    if (DEV_MODE) {
        return true;
    }
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rickw@nimexgrp.com';
        $mail->Password   = 'Nimex1445.'; // Use App Password if Google blocks this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rickw@nimexgrp.com', 'RegrowthX Support');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your RegrowthX Login Code';
        $mail->Body    = "
        <html>
        <head>
          <title>Your RegrowthX Login Code</title>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center;'>
          <h2>RegrowthX</h2>
          <p>Your login code is:</p>
          <h1 style='color: #059669; letter-spacing: 5px;'>$otpCode</h1>
          <p>This code will expire in 10 minutes.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Your RegrowthX login code is: $otpCode. This code will expire in 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Return the exact PHPMailer error for debugging
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}

function sendResetEmail($email, $resetCode) {
    if (DEV_MODE) {
        return true;
    }
    
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rickw@nimexgrp.com';
        $mail->Password   = 'Nimex1445.'; // Use App Password if Google blocks this
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rickw@nimexgrp.com', 'RegrowthX Support');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your RegrowthX Password';
        $mail->Body    = "
        <html>
        <head>
          <title>Reset Your RegrowthX Password</title>
        </head>
        <body style='font-family: Arial, sans-serif; text-align: center;'>
          <h2>RegrowthX</h2>
          <p>You requested a password reset. Your reset code is:</p>
          <h1 style='color: #059669; letter-spacing: 5px;'>$resetCode</h1>
          <p>This code will expire in 15 minutes. If you did not request this, please ignore this email.</p>
        </body>
        </html>
        ";
        $mail->AltBody = "Your RegrowthX password reset code is: $resetCode. This code will expire in 15 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
