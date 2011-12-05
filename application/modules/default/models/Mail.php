<?php
/**
 *Model for sending mail
 */

class Model_Mail
{
    /**
     *Send mail using zend mail
     *@param array of mail info. array('to'=>'test','from'=>'test','subject'=>'hello','message'=>'how are you');
     *@return boolen true for mail sent, false for not sent
     */
    public function sendMail($mailInfo)
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $mailConfig = array(
                       'ssl' => 'ssl',
                       'port' => 465,
                       'auth' => 'login',
                       'username' => $config->email->username,
                       'password' => $config->email->password
                 );
        $transport = new Zend_Mail_Transport_Smtp($config->email->host,$mailConfig);
        Zend_Mail::setDefaultTransport($transport);

        $mail = new Zend_Mail();
        $mail->setBodyText($mailInfo['message'])
            ->setFrom($config->email->fromAddress,$config->email->fromName)
            ->setSubject($mailInfo['subject']);
        
        if($mailInfo['to']){
            $mail->addTo($mailInfo['to']);
        } else {
            $mail->addTo($config->email->contact);
            $mail->addCc($config->email->cc);
        }
        
        try {
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}