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

        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')->setAttrib('id', 'Submit');
                                    
                                         
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All');
        $button->setAttrib('class', 'check-uncheck');
        
        $this->addElements($form);
        // add clearfix div for all form items
        foreach($form as $element){
            $element->addDecorators(array(array(array('wrapperAll' => 'HtmlTag') ,
                                                array('tag' => 'div' , 'class' => 'clearfix form-item'))
                                          ));
        }
        
        $this->addDisplayGroup(array('first_name', 'middle_name', 'last_name', 'user_name', 'password', 'confirmpassword', 'email'),
                                        'field1',array('legend'=>'User Information'));
        // add wrapper class for the group
        $group = $this->getDisplayGroup('field1');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        
        // add permission form
        $permissionForm = new Form_Admin_Editpermission();
        $permissionForm->edit($defaultFields);
        $permissionForm->removeDecorator('form');
        $permissionForm->removeElement('submit');
        $this->addSubForm($permissionForm , 'test');
               
        $this->addElement($signup);
        $this->setMethod('post');
    }
}