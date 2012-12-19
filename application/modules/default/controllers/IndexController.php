<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
	
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('layout_wep_index');
     
  		$form = new Form_Wep_Contact();
  		$this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
            	$data = array();
            	$data['name'] = $form->getValue('name');
            	$data['email'] = $form->getValue('email');
            	$data['message'] = $form->getValue('message');
            	
                $contact = new Model_Contact;
              	$contact->insert($data);
              	
                $mailParams['subject'] = 'Feedback for AidStream received';
		$mailParams['servername'] = $_SERVER['SERVER_NAME'];
                $mailParams['name'] =  $data['name'];
                $mailParams['email'] =  $data['email'];
                $mailParams['message'] = $data['message'];
                $template = "contact_us.phtml";
                $notification = new App_Notification;
                $notification->sendemail($mailParams,$template);
               	
                $this->_helper->FlashMessenger->addMessage(array('message' => 'Thank you for the message.'));
  				$this->_redirect('/');
  				            	
            } else {
                $errormessage = 'Please provide valid data';
                $captchaMessages = $form->captcha->getMessages();
                if(!empty($captchaMessages)){
                    $errormessage = array_pop($captchaMessages);
                }
            	$this->_helper->FlashMessenger->addMessage(array('error' => $errormessage));
            	$this->_redirect('#contact-us');
            }
        }    
    }
}

