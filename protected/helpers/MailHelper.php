<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 0:34
 * To change this template use File | Settings | File Templates.
 */

class MailHelper {
    public static function sendMail($recepientMail,$subject,$htmlText,$fromMail = "admin@mail.ru",$fromName = "admin")
    {
        if(mail($recepientMail,$subject,$htmlText)) {
        	return true;
        } else {
        	return false;
        }
//        Yii::import('application.extensions.phpmailer.JPhpMailer');
//        $mail = new JPhpMailer;
//        $mail->IsSMTP();
//        $mail->SMTPDebug = 1;
//        $mail->Host = 'smtp.mail.ru';
//        $mail->SMTPAuth = true;
//        $mail->SMTPSecure = "tls";
//        $mail->Port     = '25';
//        $mail->Timeout  = '60';
//        $mail->Username = 'redperchic@mail.ru';
//        $mail->Password = 'pasSworD';
//        $mail->SetFrom($fromMail,$fromName);
//        $mail->Subject = $subject;
//        $mail->AltBody = $subject;
//        $mail->MsgHTML($htmlText);
//        $mail->AddAddress($recepientMail);
//        $mail->Send();
    }
}