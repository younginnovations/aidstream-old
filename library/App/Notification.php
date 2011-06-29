<?php
/*
 * class class
 */

class App_Notification{
    
    protected $mailerParams;
    protected $to;
    protected $template;
    
    function __construct() {
    }
    
    public function sendemail($mailerParams,$to,$template){
        $this->mailerParams = $mailerParams;
        $this->to = $to;
        $this->template = $template;
        
        $mailer = Zend_Registry::get('mailer');
        $mailer->setTo(array($this->to => ''));
        $mailer->setTokens($this->mailerParams);
        $mailer->setTemplate($this->template);
        $result = $mailer->send();
        if($result){
          return TRUE;
        }
        else{
          return FALSE;
        }
    }
}

?>