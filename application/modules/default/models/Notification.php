<?php
/*
 * Class for Notification.i.e sending mails and saving logs.
 *
 * Currently all notifications include only emails
 */

class Model_Notification{
    
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
            $mailer->setsendBcc(true);
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
    /**
     * Send all notifications for registration.
     * Only email is used for now.
     */
    public function sendRegistrationNotifications($data)
    {
        $to = array($data['email'] => '');
        
        $mailParams['subject'] = 'Account Registration Confirmed';
        
        $mailParams['first_name'] = $data['first_name'];
        $mailParams['middle_name'] = $data['middle_name'];
        $mailParams['last_name'] = $data['last_name'];
        $mailParams['username'] = trim($data['user_name']);
        $mailParams['url'] = "http://".$_SERVER['SERVER_NAME'].Zend_Controller_Front::getInstance()->getBaseUrl();
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $regBccs = $config->email->registrationBccs;
        if($regBccs){ 
            $bccs = explode("," , $regBccs);
            foreach($bccs as $bcc){
                $to[$bcc] = '';
            }
        }

        $template = 'user-register.phtml';
        $this->sendemail($mailParams , $template , $to);
    }
    
    /**
     * Send notification for support query.
     */
    public function sendSupportNotifications($data)
    {
        $model = new Model_Wep();
        $account = $model->getRowById('account', 'id', Zend_Auth::getInstance()->getIdentity()->account_id);
        
        //Send Support Mail
        $mailParams['subject'] = 'Support Request';
        $mailParams['support_name'] = $data['support_name'];
        $mailParams['support_email'] = $data['support_email'];
        $mailParams['support_query'] = $data['support_query'];
        $mailParams['from'] = array($data['support_email'] => '');
        $mailParams['servername'] = $_SERVER['SERVER_NAME'];
        $mailParams['account_name'] = $account['name'];
        
        $supportEmail = Zend_Registry::get('config')->email->support;
        
        $template = 'support.phtml';
        $this->sendemail($mailParams , $template , array( $supportEmail => ''));
    }
    
    /**
     * Send notification for forgot password.
     */
    public function sendResetNotifications($user , $resetUrl)
    {
        $email = $user->email;
        $profileModel = new User_Model_DbTable_Profile();
        $profile = $profileModel->getProfileByUserId($user->user_id);
        $name = $profile->first_name;
        if($profile->middle_name){
            $name .= " ".$profile->middle_name;
        }
        $name .= " ".$profile->last_name;

        $mailParams['subject'] = 'Password reset for ' . $email;
        $mailParams['name'] = $name;
        $mailParams['username'] = $user->user_name;
        $mailParams['reset_url'] = $resetUrl;
        
        $template = 'forgot_password.phtml';
        $this->sendemail($mailParams, $template, array($email => ''));
    }
    
}

?>