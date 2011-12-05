<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('layout_wep');
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
              	
                $mail['subject'] = 'Feedback for AidStream received';                

                $mail['message'] = 'The following user provided feedback:';
                $mail['message'] .=  "\nName: ".$data['name'];
                $mail['message'] .=  "\nEmail: ".$data['email'];
                $mail['message'] .= "\n\nMessage:\n".$data['message'];
                
                $modelMail = new Model_Mail();
                $modelMail->sendMail($mail);
               	$this->_helper->FlashMessenger->addMessage(array('message' => 'Thank you for the message.'));
  				$this->_redirect('/');
  				            	
            } else {
            	$this->_helper->FlashMessenger->addMessage(array('error' => 'Please provide valid data'));
            	$this->_redirect('#contact-us');
            }
        }    
    }
}

