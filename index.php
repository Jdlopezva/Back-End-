<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';

// Takes raw data from the request
$json = file_get_contents('php://input');

// data webhook
$data = json_decode($json);

//form id afrus
$formid = $data->data->form_id;

$first_name = $data->data->first_name;
$last_name = $data->data->last_name;
$sender_emailuser = $data->data->sender_emailuser;
$receiver_emailuser = $data->data->receiver_emailuser;

$passuser = randomPassword();

$parts = explode('@', $emailuser);
$user = $parts[0];//username


//validate exist user
$validate =  validateuser($emailuser);
$validate_user_data = json_decode($validate);

//validate form afrus
if($formid == 153){
    $idtarjeta = 12293; // dia madres 
    if (empty($validate_user_data)) {
        adduser($user, $emailuser, $passuser, $idcurso, $first_name, $last_name);//agregar tipo de tarjetas
   }else{
        addusercourse_only($validate_user_data[0]->id, $idcurso, $validate_user_data[0]->name, $emailuser);//solo enviar tarjeta
   }
    
}elseif ($formid == 154) {
    $idtarjeta = 12485; //consagracion
    if (empty($validate)) {
        adduser($user, $emailuser, $passuser, $idcurso, $first_name, $last_name);//agregar usuario y curso
    }else{
        addusercourse_only($validate_user_data[0]->id, $idcurso, $validate_user_data[0]->name, $emailuser);//agregar solo al curso
    }
}









//send email
function sendemail($name_client, $email_client, $course, $pass_client){

    
    // Replace sender@example.com with your "From" address.
    // This address must be verified with Amazon SES.
    $sender = 'tarjetas@abaco.org';
    $senderName = 'Tarjetas Abaco';
    
    // Replace recipient@example.com with a "To" address. If your account
    // is still in the sandbox, this address must be verified.
    $recipient = $email_client;
    
    // Replace smtp_username with your Amazon SES SMTP user name.
    $usernameSmtp = '';
    
    // Replace smtp_password with your Amazon SES SMTP password.
    $passwordSmtp = '';
    
    
    // If you're using Amazon SES in a region other than US West (Oregon),
    // replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
    // endpoint in the appropriate region.
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;
    
    // The subject line of the email
    $subject = 'Tarjeta de regalo Abaco ';
    
    // The plain-text body of the email
    $bodyText =  "Email enviado desde Amazon SES por Abaco";
    
    // The HTML-formatted body of the email
    $bodyHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    
    <head>
        <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <title></title>
        <!--[if !mso]><!-->
        <!--<![endif]-->
        <style type="text/css">
           
        </style>
    </head>
    
    <body>

    </body>
    
    </html>';
    
    $mail = new PHPMailer(true);
    
    try {
        // Specify the SMTP settings.
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
    
        // Specify the message recipients.
        $mail->addAddress($recipient);
        // You can also add CC, BCC, and additional To recipients here.
    
        // Specify the content of the message.
        $mail->isHTML(true);
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;
        $mail->AltBody    = $bodyText;
        $mail->Send();
        echo "Email sent!" , PHP_EOL;
    } catch (phpmailerException $e) {
        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }
    
    

}

//send email only course
function sendemail_course($name_client, $email_client, $course){

    
    // Replace sender@example.com with your "From" address.
    // This address must be verified with Amazon SES.
    $sender = 'email@caballerosdelavirgen.org';
    $senderName = 'Caballeros';
    
    // Replace recipient@example.com with a "To" address. If your account
    // is still in the sandbox, this address must be verified.
    $recipient = $email_client;
    
    // Replace smtp_username with your Amazon SES SMTP user name.
    $usernameSmtp = 'AKIAXUAQ7MXMTRVAFZAO';
    
    // Replace smtp_password with your Amazon SES SMTP password.
    $passwordSmtp = 'BJu9KwjIukW5inPUoRLKNmroZraU88MB/9tP0JYh7NDW';
    
    
    // If you're using Amazon SES in a region other than US West (Oregon),
    // replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
    // endpoint in the appropriate region.
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;
    
    // The subject line of the email
    $subject = 'Caballeros de la virgen | Cursos';
    
    // The plain-text body of the email
    $bodyText =  "Email enviado desde Amazon SES";
    
    // The HTML-formatted body of the email
    $bodyHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    
    <head>
        <!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width">
        <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <title></title>
        <!--[if !mso]><!-->
        <!--<![endif]-->
        <style type="text/css">
           
        </style>
    </head>
    
    <body>

    </body>
    
    </html>';
    
    $mail = new PHPMailer(true);
    
    try {
        // Specify the SMTP settings.
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);
        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
    
        // Specify the message recipients.
        $mail->addAddress($recipient);
        // You can also add CC, BCC, and additional To recipients here.
    
        // Specify the content of the message.
        $mail->isHTML(true);
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;
        $mail->AltBody    = $bodyText;
        $mail->Send();
        echo "Email sent!" , PHP_EOL;
    } catch (phpmailerException $e) {
        echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
    } catch (Exception $e) {
        echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
    }
    
    

}


function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
