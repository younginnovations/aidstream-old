<?php
class Form_Wep_Contact extends App_Form
{
    public function init($option = NULL)
    {
        $this->setName('Name');
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
        	->setRequired()
        	->setAttrib('class','input_box name');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
        	 ->setRequired()
        	 ->setAttrib('class','input_box email');
        	 
       	$message = new Zend_Form_Element_Textarea('message');
       	$message->setLabel('Message')
       			->setAttrib('rows', '8')
       			->setRequired()
       			->setAttrib('class','input_box message');

        $submit = new Zend_Form_Element_Submit('send');
        $submit->setLabel('Send');

        $this->addElements(array($name,$email,$message,$submit));
        $this->setMethod('post');
        }
}//end of class