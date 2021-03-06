<?php

namespace App\Http\Services;

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

class MailerService
{
    /**
     * Call send email to user
     * @param email
     */
    public function sendEmail($email)
    {
        $mail = new PHPMailer(true);
        return $this->sendEmail($email);
    }

    /**
     * Construct email and send it via phpmailer.
     * @param PHPMailer
     * @param email
     */
    public function send(PHPMailer $mail, $email)
    {
        //$mail->SMTPDebug = 2;                            // Enable verbose debug output
        $mail->isSMTP();                                   // Set mailer to use SMTP
        $mail->Host = env('MAIL_HOST');                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                            // Enable SMTP authentication
        $mail->Username = env('MAIL_USERNAME');            // SMTP username
        $mail->Password = env('MAIL_PASSWORD');            // Password of the account from which emails are sended (in this case it is hashed)
        $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
        $mail->Port = env('MAIL_PORT');                    // TCP port to connect to

        //Recipients
        $mail->setFrom(env('ADMIN_EMAIL'), 'UciExcel Kalkulator');

        // address
        $mail->addAddress($email['email']);

        //Content
        $mail->isHTML(true);                               // Set email format to HTML
        $mail->Subject = $email['subject'];
        $mail->Body    = $email['message'];
        //$mail->AltBody = 'Alt body';
        //dd($mail);
        $mail->send();
        echo 'Message has been sent';
    }
}