<?php
class Form_Wep_Contact extends App_Form
{
    public function init($option = NULL)
    {
        $this->setName('Name');
	$this->setAttrib('id','contact-form');
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
        	->setRequired();
        	//->setAttrib('class','input_box name');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
        	 ->setRequired();
        	 //->setAttrib('class','input_box email');
        	 
       	$message = new Zend_Form_Element_Textarea('message');
       	$message->setLabel('Message')
       			->setAttrib('rows', '8')
       			->setRequired();
       			//->setAttrib('class','input_box message');
                        
        $publickey = '6Ld6RM0SAAAAANYtQD4j-0THK1HBXLUhAsQCXyiH';
        $privatekey = '6Ld6RM0SAAAAAJlk5mZV9tZ65xfrmHEoXtmYdyHz';
        $recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);

        $captcha = new Zend_Form_Element_Captcha('captcha',
            array(
                'captcha'       => 'ReCaptcha',
                'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha),
                'ignore' => true
                )
        );

        $submit = new Zend_Form_Element_Submit('send');
        $submit->setLabel('Send');

        $this->addElements(array($name,$email,$message,$captcha,$submit));
        $this->setMethod('post');
        }
}//end of class
