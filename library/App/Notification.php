<?php
/*
 * Class for Notification.i.e sending mails and saving logs.
 */

class App_Notification{
    
    /**
     * Centralized function to send email. Uses App_Email class for sending mail.
     * @param array $mailParams array of parameters for email. eg. subject, data to be used in message.
     * @param string $template  name of the template file to be used as template for sending mail with .phtml filetype
     * @param array $to         array of email address and name of the recipients. e.g array('test@abc.com'=>'name');
     */
    public function sendemail($mailParams, $template , $to = false)
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        
        $mailer = Zend_Registry::get('mailer');
        if($to){
            $mailer->setSeparateCc(true);
        }
        // Add admin emails as recipient.
        $to[$config->email->to] = '';
        $mailer->setTo($to);        
        $mailer->setTokens($mailParams);
        $mailer->setTemplate($template);
        $result = $mailer->send();
        
        if($result){
          return TRUE;
        } else {
          return FALSE;
        }
    }
}

?>