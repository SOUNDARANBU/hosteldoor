<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 29/5/2018
 * Time: 6:41 PM
 */

namespace manager;
global $C;
require_once($C->dirroot.'/library/phpmailer/PHPMailer.php');
require_once($C->dirroot.'/library/phpmailer/SMTP.php');
require_once($C->dirroot.'/library/phpmailer/Exception.php');

class email
{
    public static function send_email($email_data){
        global $C;
        if($C->enable_email){
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            try {
                //Server settings
                $mail->SMTPDebug = 0;  // Enable verbose debug output
                $mail->isSMTP();  // Set mailer to use SMTP
                $mail->Host = $C->Host;  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;  // Enable SMTP authentication
                $mail->Username = $C->Username; // SMTP username
                $mail->Password = $C->Password;  // SMTP password
                $mail->SMTPSecure = $C->SMTPSecure;  // Enable TLS encryption, `ssl` also accepted
                $mail->Port = $C->Port;  // TCP port to connect to

                //Recipients
                $mail->setFrom($email_data->fromemail, $email_data->fromusername);
                $mail->addAddress($email_data->toemail, $email_data->tousername);     // Add a recipient
                //  $mail->addAddress('ellen@example.com');               // Name is optional
                $mail->addReplyTo($email_data->replyemail, $email_data->username);
                //  $mail->addCC('cc@example.com');
                //  $mail->addBCC('bcc@example.com');

                //Attachments
                $mail->addAttachment($email_data->attachment_path);         // Add attachments
                //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $email_data->subject;
                $mail->Body    = $email_data->body;
                $mail->AltBody = $email_data->body;

                $mail->send();

                if($mail->isError()){
                    $log = new log();
                    $log->write_log($mail->ErrorInfo);
                    return false;
                }
                return true;
                // echo 'Message has been sent';
            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }
        }else{
            return false;
        }
    }
}