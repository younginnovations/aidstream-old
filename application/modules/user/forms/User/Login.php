<?php
class User_Form_User_Login extends App_Form
{
    public function init($option = NULL)
    {
       // parent::_contruct($option);
        $this->setName('Login');
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')->setRequired()
        	->setAttrib('class','input_box username');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
        	 ->setAttrib('class','input_box password')->setRequired();

        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login');

        $this->addElements(array($username,$password,$login));
        $this->setMethod('post');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/user/user/login');
    }
}//end of class