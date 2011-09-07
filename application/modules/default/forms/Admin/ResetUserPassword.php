<?php
class Form_Admin_ResetUserPassword extends App_Form
{
    public function init()
    {
        $form = array();
        
        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')->setRequired()->addValidator($passwordConfirmation)
        ->setAttrib('class', 'form-text');

        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')->setAttrib('class', 'form-text')
        ->setRequired()->addValidator($passwordConfirmation);

        $this->addElements(array($oldpassword, $password, $confirmPassword, $submit));
        $this->addDisplayGroup(array('password', 'confirmpassword'), 'field1',array('legend'=>'Reset User Password'));
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('change')->setAttrib('class', 'form-submit');
        $this->addElement($submit);
        $this->setMethod('post');
    }
    
}