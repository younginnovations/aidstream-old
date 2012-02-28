<?php

class User_Form_User_Changepassword extends App_Form
{

    public function init()
    {
        $form = array();

        $oldpassword = new Zend_Form_Element_Password('oldpassword');
        $oldpassword->setRequired()->setLabel('Old Password')
        ->setDescription('Enter your current password.')
        ->setAttrib('class', 'form-text');

        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')->setRequired()->addValidator($passwordConfirmation)
        ->setAttrib('class', 'form-text');


        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')->setAttrib('class', 'form-text')
        ->setRequired()->addValidator($passwordConfirmation);



        $this->addElements(array($oldpassword, $password, $confirmPassword, $submit));
        foreach($this->getElements() as $item)
        {
            $item->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        $this->addDisplayGroup(array('oldpassword', 'password', 'confirmpassword'), 'field1',array('legend'=>'Change Password'));
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('change')->setAttrib('class', 'form-submit');
        $this->addElement($submit);
        $this->setMethod('post');
        foreach($this->getElements() as $element){
            $element->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'form-item'))
            ));
        }
    }

}