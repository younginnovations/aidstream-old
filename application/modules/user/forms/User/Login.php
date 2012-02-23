<?php
class User_Form_User_Login extends App_Form
{
    public function init($option = NULL)
    {
       // parent::_contruct($option);
       
        $this->setName('Login')
	    ->setAttrib('id' , "user-login")
	    ->setMethod('post')
	    ->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/user/user/login');
	    
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')
            ->setRequired()
            ->setAttrib('class','input_box username form-text');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setRequired()
            ->setAttrib('class','input_box password form-text');

        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login');
	
        $this->addElements(array($username,$password));
	foreach($this->getElements() as $item)
        {
            $item->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
	$this->addDisplayGroup(
                               array('username' , 'password'),
                               'login-form',
                               array('legend'=> 'Log In')
                            );
	$loginForm = $this->getDisplayGroup('login-form');
        $loginForm->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        
        $this->addElement($login);
    }
}//end of class