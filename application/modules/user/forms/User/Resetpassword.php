<?php

class User_Form_User_Resetpassword extends App_Form
{

    public function init()
    {
        $this->setName('Reset');
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')->setRequired();

        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')->setRequired()->addValidator($passwordConfirmation);



        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')->setRequired()->addValidator($passwordConfirmation);

        $login = new Zend_Form_Element_Submit('save');
        $login->setLabel('Save');

        $this->addElements(array($email, $password, $confirmPassword, $login));
        $this->setMethod('post');
        foreach($this->getElements() as $item)
        {
            $item->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
    }

}