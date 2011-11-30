<?php

class User_Form_User_Forgotpassword extends App_Form
{

    public function init()
    {

        $this->setName('Forgot Password');
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')->setRequired();

        $login = new Zend_Form_Element_Submit('reset_password');
        $login->setLabel('Reset Password');

        $this->addElements(array($email, $login));
        $this->setMethod('post');
    }

}