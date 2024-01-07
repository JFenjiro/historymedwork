<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception as MailerException;
    use PHPMailer\PHPMailer\SMTP;

    require './vendor/phpmailer/phpmailer/src/Exception.php';
    require './vendor/phpmailer/PHPMailer/src/PHPMailer.php';
    require './vendor/phpmailer/PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'historymedwork';                     //SMTP username
        $mail->Password   = 'egfcnwmgfnxlzwhq';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`



        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name


        echo 'Message has been sent';
    } catch (MailerException $e) {
        echo "Le message n'a pas pu être envoyé. Mailer Error : {$mail->ErrorInfo}";
    }
    
    
    
    