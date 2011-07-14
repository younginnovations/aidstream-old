<?php
class Form_Admin_Accountregister extends App_Form
{
    public function add($defaultFields = '', $state = 'add')
    {
        $form = array();

        $form['first_name'] = new Zend_Form_Element_Text('first_name');
        $form['first_name']->setLabel('First Name')->setRequired();

        $form['middle_name'] = new Zend_Form_Element_Text('middle_name');
        $form['middle_name']->setLabel('Middle Name');

        $form['last_name'] = new Zend_Form_Element_Text('last_name');
        $form['last_name']->setLabel('Last Name')->setRequired();

        $form['user_name'] = new Zend_Form_Element_Text('user_name');
        $form['user_name']->setLabel('User Name')->addValidator('Db_NoRecordExists', false, array('table' => 'user',
                                                               'field' => 'user_name'))->setRequired();

        $passwordConfirmation = new App_PasswordConfirmation();
        $form['password'] = new Zend_Form_Element_Password('password');
        $form['password']->setLabel('Password')->setRequired()->addValidator($passwordConfirmation);

        $form['confirmpassword'] = new Zend_Form_Element_Password('confirmpassword');
        $form['confirmpassword']->setLabel('Confirm Password')->setAttrib('class', 'input_box confirmpassword');
        $form['confirmpassword']->setRequired()->addValidator($passwordConfirmation);
        
        $form['email'] = new Zend_Form_Element_Text('email');
        $form['email']->setLabel('Email')
        ->addValidator('emailAddress', false)
        ->addFilter('stringTrim')
        ->setRequired();
                                        
        $form['default_fields'] = new Zend_Form_Element_MultiCheckbox('default_fields');
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            $form['default_fields']->addMultiOption($key, ucwords(str_replace("_", " ", $key)));
            if($eachDefault == '1'){
                $checked[] = $key;
            }
        }
        $form['default_fields']->setValue($checked);
        
        //@todo reCaptcha

        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')->setAttrib('id', 'Submit');
                                         
        $this->addElements($form);
        
        
        $this->addDisplayGroup(array('first_name', 'middle_name', 'last_name', 'user_name', 'password', 'confirmpassword', 'email'),
                                        'field1',array('legend'=>'User Information'));
       
        $this->addDisplayGroup(array('default_fields',), 'field3',array('legend'=>'Default Field Groups'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}