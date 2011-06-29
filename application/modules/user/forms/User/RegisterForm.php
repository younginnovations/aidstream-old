<?php

class User_Form_User_RegisterForm extends App_Form
{

    public function init()
    {
        $this->setName('Register');
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Full Name')->setRequired();


        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')->setRequired()->addValidator('emailAddress', false)
                ->addValidator('Db_NoRecordExists', false, array('table' => 'user',
                    'field' => 'email'))
                ->setDescription('All emails from the system will be sent to this address. The email address is not made public and will only be used if you wish to receive a new password.');


//        $mobile = new Zend_Form_Element_Text('mobile');
//        $mobile->setLabel('Phone No')
//                ->addValidator('int', false)
//                ->setRequired();

        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')->setRequired()->addValidator($passwordConfirmation);


        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')->setRequired()->addValidator($passwordConfirmation);

        $create = new Zend_Form_Element_Submit('create_new_account');
        $create->setLabel('Sign Up');

        $this->addElements(array($username, $email, $password, $confirmPassword, $create));
        $this->setMethod('post');
    }

}