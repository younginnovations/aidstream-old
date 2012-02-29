<?php

class User_Form_User_Resetpassword extends App_Form
{

    public function init()
    {
        $this->setName('Reset');
        $this->setMethod('post');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setAttrib('class', 'form-text')
            ->setRequired();

        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);



        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);

        $login = new Zend_Form_Element_Submit('save');
        $login->setLabel('Save')
            ->setAttrib('class', 'form-submit');

        $this->addElements(array($email, $password, $confirmPassword));
        $this->addDisplayGroup(array('email', 'password', 'confirmpassword'), 'reset_password',array('legend'=>'Reset Password'));
        $this->addElement($login);
        
        $displayGroup = $this->getDisplayGroup('reset_password');
        $displayGroup->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        foreach($this->getElements() as $item)
        {
            $item->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
    }

}