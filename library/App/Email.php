<?php
/**
 * Yipl Email Class
 * 
 * Email Class
 * 
 * @uses       Zend_Mail
 * @package    App
 */

class App_Email 
{
    private $_body_plain;
    private $_body_html;
    private $_to;
    private $_from;
    private $_subject;
    private $_config;
    private $_tokens;
    private $_logger;
    private $_send_bcc;
    private $_separate_cc;    // true if you want each recipient to get their own copy.  otherwise all emails are shown in email.


    /**
     *  initialize Email Class with token and 'to' properties
     */
    public function __construct($tokens=array(), $to=array())
    {
        $this->_config = Zend_Registry::get('config');
        $this->_logger = Zend_Registry::get('logger');
        $this->setTo($to);
        $this->setTokens($tokens);
        $this->setSeparateCc(false);
    }


    /**
     * Sets and compiles E-mail templates for both plain and html
     */
    public function setTemplate($template)
    {
        $tempPath = $this->_config->email->templatesPath;
        $this->_template = $template;
        $myView = new Zend_View;
        $myView->setScriptPath($tempPath);
        $hasContent = false;

        // compile html template if available
        $fileName = 'html.'.$template;
        $filePath = $tempPath.'/'.$fileName;

        if ( is_file($filePath) ) {
            $this->_logger->info('Custom_Email::setTemplate(): Rendering HTML "' . $template . '" template.');
            $viewHtml = clone $myView;
            $this->_body_html = $this->applyTokens($viewHtml, $fileName);
            $hasContent = true;
        }

        // compile plain text template if available
        $fileName = 'plain.'.$template;
        $filePath = $tempPath.'/'.$fileName;

        if ( is_file($filePath) ) {
            $this->_logger->info('Custom_Email::setTemplate(): Rendering plain text "' . $template . '" template.');
            $viewPlain = clone $myView;
            $this->_body_plain = $this->applyTokens($viewPlain,$fileName);
            $hasContent = true;
        }
            
        if ( !$hasContent ) {
            $this->_logger->err('Custom_Email::setTemplate(' . $template . '): Unable to find e-mail template.');
        }
    }


    public function getPlain()
    {
        return $this->_body_plain;
    }
    
    public function getHtml()
    {
        return $this->_body_html;
    }
    
    /**
     *
     * @param Array $from coantains email as key and name as value
     */
    public function setFrom($from)
    {
        $this->_from = $from;
    }
    
    /**
     * @return Array from value.
     */
    public function getFrom()
    {
        return $this->_from;
    }
    
    /**
     * Set if mail should be sent as bcc to receivers.
     */
    public function setsendBcc($bcc = false)
    {
        $this->_send_bcc = $bcc;
    }
    
    /**
     * Check if send as bcc is set.
     * 
     * @return Boolen
     */
    public function sendBcc()
    {
        return $this->_send_bcc;
    }


    /**
     * for bypassing templates
     */
    public function setPlain($text)
    {
        $this->_body_plain = $text;
    }


    /**
     * for bypassing templates
     */
    public function setHtml($text)
    {
        $this->_body_html = $text;
    }


    /**
     * Sets the email's subject
     * 
     * @param $subject
     */
    public function setSubject($subject=array())
    {
        $this->_subject = $subject;
    }


    /**
     * Sets the email's recipients
     * 
     * @param $to
     */
    public function setTo($to=array())
    {
        $this->_to = $to;
    }


    public function getSeparateCc()
    {
        return $this->_separate_cc;
    }


    public function setSeparateCc($value)
    {
        $this->_separate_cc = $value;
    }

    public function getTokens()
    {
        return $this->_tokens;
    }
    /**
     * Sets the tokens that will be inserted into the email template
     * 
     * @param $tokens
     */
    public function setTokens($tokens=array())
    {
        $this->_tokens = $tokens;
        if ( isset($tokens['subject']) ) $this->setSubject($tokens['subject']);
        if (isset($tokens['from']) ) $this->setFrom($tokens['from']);
    }
    

    /**
     * applies the tokens to the template
     */
    public function applyTokens($view, $fileName)
    {
        if ( is_array($this->_tokens) && count($this->_tokens) ) {
            /* assign values to associated template tokens */
            foreach($this->_tokens AS $key => $value){
                $view->assign($key, $value);
            }
        } else {
            $this->_logger->err('Custom_Email::applyTokens(): Setting an empty array of e-mail tokens!');
        }

        return $view->render($fileName);
    }
     
    /**
     * prepares e-mail for dev & stage
     * @todo: TODO: check that html email works.  may prepend above html content
     */
    private function _prepareDevEmail($mail)
    {
        /*-- prepare non production info --*/
        $prepend = 'This email has been redirected to you because it was sent from ' . strtoupper(APPLICATION_ENV);
        $prepend.= "\n\nOriginal recipients:";

        $recipients = $mail->getRecipients();
        
        foreach ( $recipients AS $address ) {
            $prepend .= "\n$address";
        }

        $prepend .= "\n\n-----------------------------\n\n";

        if ( strlen($this->_body_plain) ) {
            $mail->setBodyText($prepend . $this->_body_plain);
        }

        if ( strlen($this->_body_html) ) {
            $mail->setBodyHtml(nl2br($prepend) . $this->_body_html);
        }

        $body = ( strlen($this->_body_plain) ) ? $this->_body_plain : $this->_body_html;
        
        $this->_logger->debug('Dev Email Redirect: ' . nl2br($prepend . $body));
        return $mail;
    }

    
   /**
    * Actually sends the email.  Redirects to dev if needed.
    * 
    * @param Zend_Mail $mail
    */
    private function _performSend($mail)
    {
        if ( APPLICATION_ENV == 'production' || APPLICATION_ENV == 'staging' ) {
            try {
                $mail->send();
                $this->_logger->notice('Send Email: email addressed to ' . implode(',', $mail->getRecipients()) );
            } catch (Exception $e) {
                $this->_logger->crit('Send Email: Error sending email to ' . implode(',', $mail->getRecipients()));
            }
            
        } else {
            // development has all emails redirected to the default recipient
            $auth = Zend_Auth::getInstance();
            $mail = $this->_prepareDevEmail($mail);    // add a dev note to top of email
            $mail->clearRecipients();
            
           
            $mail->addTo($this->_config->email->bcc,'Test User');
            $this->_logger->notice("Dev email redirected to " . $this->_config->email->bcc);

            try {
                $mail->send();
                $this->_logger->notice("Send Email: Dev email has been sent.");
            } catch (Exception $e) {
                $this->_logger->crit('Send Email: Error sending dev email.');
                //$this->_logger->err($e);     // causes an infinite loop since logger sends an email 
            }
        }
    }


    /**
     * Sends the email.
     */
    public function send()
    {
        if($this->_config->smtp->enabled){
            // Use smtp transport for sending using smtp server.
            $mailConfig = array(
                           'ssl' => $this->_config->smtp->ssl,
                           'port' => $this->_config->smtp->port,
                           'auth' => $this->_config->smtp->auth,
                           'username' => $this->_config->smtp->username,
                           'password' => $this->_config->smtp->password
                     );
            $transport = new Zend_Mail_Transport_Smtp($this->_config->smtp->host,$mailConfig);
            Zend_Mail::setDefaultTransport($transport);
        }
        
        $mail = new Zend_Mail();
        $mail->setSubject($this->_subject)
            ->setFrom($this->_config->email->fromAddress, $this->_config->email->fromName);
        
        $this->setHeaders($mail);
        $this->setFromHeaders($mail);

        if ( strlen($this->_body_plain) ) {
            $mail->setBodyText($this->_body_plain);
        }

        if ( strlen($this->_body_html) ) {
            $mail->setBodyHtml($this->_body_html);
        }

        $to_string = '';
        foreach ( $this->_to AS $address => $name ) {
            if ( $to_string != '' ) $to_string .= ', ';
            $to_string .= "$name &lt;$address&gt;";
        }
        
        $separate_cc = ( $this->getSeparateCc() ) ? ' (separate emails)' : ' (one email)';
        $this->_logger->info('Email Recipients ' . $separate_cc . ': ' . $to_string);
        $this->_logger->info('Email Subject: ' . $mail->getSubject());
        if ( strlen($this->_body_plain) ) $this->_logger->info('Email Body: ' . nl2br($this->_body_plain));
        
        if ( is_array($this->_to) && count($this->_to) ) {

            if ( $this->getSeparateCc() ) {

                // Send each recipient their own copy of the email
                foreach ( $this->_to AS $address => $name ) {
                    $mail->clearRecipients();
                    $mail->addTo($address, $name);
                    $this->_performSend($mail);
                }
                
            } else if ($this->sendBcc()) {
                 // Add all recipients to the same email as bcc and send just one email
                $mail->clearRecipients();
                $recipients = $this->_to;
                $to = current(array_keys($recipients));
                $mail->addTo( $to , current(array_values($recipients)));// set the first email as to and other as bcc
                
                unset($recipients[$to]);
                
                foreach ( $recipients AS $address => $name ) {
                    $mail->addBcc($address);
                }

                $this->_performSend($mail);

            } else {

                // Add all recipients to the same email and send just one email
                $mail->clearRecipients();
                
                foreach ( $this->_to AS $address => $name ) {
                    $mail->addTo($address, $name);
                }

                $this->_performSend($mail);
            } 
            
        } else {
            $this->_logger->crit('No recipients defined for this email.  Subject:' . $mail->getSubject());
        }

        /* for SMTP relaying
        $config = array(
                        'ssl' => 'tls',
                        'port' => 587,
                        'auth' => 'login',
                        'username' => $this->_config->email->username,
                        'password' => $this->_config->email->password
                  );
        $transport = new Zend_Mail_Transport_Smtp($this->_config->email->host,$config);
        */
    }
    
    /**
     * Sets required headers for the mail object
     *
     * @param Zend_Mail
     */
    public function setHeaders(&$mail)
    {
        $mail->addHeader('MIME-Version', '1.0')
            ->addHeader('Content-Transfer-Encoding', '8bit')
            ->addHeader('X-Mailer:', 'PHP/'.phpversion());            
    }
    
    /**
     * Set return path and reply to headers based on from values provided
     *
     * @param Zend_Mail
     */
    public function setFromHeaders(&$mail)
    {
        if( $this->_from && !empty($this->_from) ) {
            $from = $this->_from;
            $mailer = current(array_keys($from));
            $name = current(array_values($from));
            $mail->setReturnPath($mailer, $name)->setReplyTo($mailer, $name);
            
        } else {
            $mail->setReturnPath($this->_config->email->replyTo, $this->_config->email->fromName)
                ->setReplyTo($this->_config->email->replyTo, $this->_config->email->fromName);
        }
    }

}