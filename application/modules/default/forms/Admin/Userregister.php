<?php
class Form_Admin_Userregister extends App_Form
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
                                        
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            if($key == 'add_activity' || $key == 'add_activity_elements'){
                $key = 'add';
                $default_fields['add'] = 'Add';
            }
            elseif($key == 'edit_activity' || $key == 'edit_activity_elements'){
                $key = 'edit';
                $default_fields['edit'] = 'Edit';
            }
            else if($key == 'delete_activity' || $key == 'delete_activity_elements'){
                $key = 'delete';
                $default_fields['delete'] = 'Delete';
            }
            else if($key == 'view_activities'){
                continue;
            }
            else{
                $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
            }
            if($eachDefault == '1'){
                $checked[] = $key;
            }
        }
        $this->addElement('multiCheckbox', 'default_fields', array(
                        'disableLoadDefaultDecorators' => true,
                        'separator'    => '&nbsp;',
                        'multiOptions' => $default_fields,
                        'value' => $checked,
                        'decorators'   => array(
                                    'ViewHelper',
                                    'Errors',
                                array('HtmlTag', array('tag' => 'p'))          
                        )
        ));
        
        //@todo reCaptcha

        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')->setAttrib('id', 'Submit');
                                    
                                         
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All');
        $button->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
        $this->addElements($form);
        
        
        $this->addDisplayGroup(array('first_name', 'middle_name', 'last_name', 'user_name', 'password', 'confirmpassword', 'email'),
                                        'field1',array('legend'=>'User Information'));
       
        $this->addDisplayGroup(array('button', 'default_fields',), 'field3',array('legend'=>'User Permission'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}