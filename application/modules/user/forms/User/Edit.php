<?php

class User_Form_User_Edit extends App_Form
{

    public function init()
    {
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')->setRequired();

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')->setRequired();

//        $mobile = new Zend_Form_Element_Text('mobile');
//        $mobile->setLabel('Phone No')
//                ->addValidator('int', false)
//                ->setRequired();

        $save = new Zend_Form_Element_Submit('Save');
        $save->setValue('save');

        $this->addElements(array($username, $email, $save));
        $this->setMethod('post');
    }

}