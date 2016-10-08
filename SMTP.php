<?php
require_once('phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $smtp_host; // SMTP server
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = $smtp_host; // sets the SMTP server
$mail->Port       = $smtp_port;                    // set the SMTP port for the GMAIL server
$mail->Username   = $smtp_username; // SMTP account username
$mail->Password   = $smtp_password;        // SMTP account password
?>