<?php

class User_Form_User_Changepassword extends App_Form
{

    public function init()
    {
        $form = array();

        $oldpassword = new Zend_Form_Element_Password('oldpassword');
        $oldpassword->setRequired()->setLabel('Old Password')
                ->setDescription('Enter your current password.');

        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')->setRequired()->addValidator($passwordConfirmation);


        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')->setRequired()->addValidator($passwordConfirmation);

        $submit = new Zend_Form_Element_Submit('Change');
        $submit->setValue('change');

        $this->addElements(array($oldpassword, $password, $confirmPassword, $submit));
        $this->setMethod('post');
    }

}