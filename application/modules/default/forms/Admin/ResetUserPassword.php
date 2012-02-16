<?php
class Form_Admin_ResetUserPassword extends App_Form
{
    public function init()
    {
        $form = array();
        
        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(' New Password')
            ->setRequired()
            ->addValidator($passwordConfirmation)
            ->addDecorators( array(
                                array(
                                    array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item')
                                )
                            )
                           )
            ->setAttrib('class', 'form-text');

        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')
            ->setAttrib('class', 'form-text')
            ->setRequired()
            ->addDecorators( array(
                                array(
                                    array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item')
                                )
                            )
                           )
            ->addValidator($passwordConfirmation);

        $this->addElements(array($oldpassword, $password, $confirmPassword));
        $this->addDisplayGroup(array('password', 'confirmpassword'), 'field1',array('legend'=>'Reset User Password'));
        $group = $this->getDisplayGroup('field1');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('change')->setAttrib('class', 'form-submit');
        $this->addElement($submit);
        $this->setMethod('post');
    }
    
}