<?php
class Form_Wep_Accountregister extends App_Form
{
    public function add($defaults = '', $state = 'add')
    {
        $form = array();

        $model = new Model_Wep();
        
        $form['organisation_name'] = new Zend_Form_Element_Text('organisation_name');
        $form['organisation_name']->setLabel('Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');

        $form['organisation_address'] = new Zend_Form_Element_Textarea('organisation_address');
        $form['organisation_address']->setLabel('Address')
            ->setAttrib('rows', '4')
            ->setRequired()
            ->setAttrib('class', 'form-textarea');

        $form['organisation_username'] = new Zend_Form_Element_Text('organisation_username');
        $form['organisation_username']->setLabel("Username Prefix <a href='#' id='suffix'>?</a>")
            ->setRequired()
            ->addValidator('Db_NoRecordExists', false, array('table' => 'account','field' => 'username'))
            ->addErrorMessage('Prefix is already used')
            ->setDescription('<div class="popup">This name will be prefixed to all the usernames'
                    .'created for the organisation (eg if the prefix is ABC then the username will'
                    .'be ABC_admin for admin user.)</div>')
            ->setAttrib('class', 'form-text')
            ->setDecorators(array(
                    'ViewHelper',
                    array('Description', array('escape' => false, 'tag' => false)),
                    array('HtmlTag', array('tag' => 'dd')),
                    array('Label', array('tag' => 'dt')),
                    'Errors',
                ));

        $form['first_name'] = new Zend_Form_Element_Text('first_name');
        $form['first_name']->setLabel('First Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');

        $form['middle_name'] = new Zend_Form_Element_Text('middle_name');
        $form['middle_name']->setLabel('Middle Name')
            ->setAttrib('class', 'form-text');

        $form['last_name'] = new Zend_Form_Element_Text('last_name');
        $form['last_name']->setLabel('Last Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');


        $form['admin_username'] = new Zend_Form_Element_Text('admin_username');
        $form['admin_username']->setLabel('Username')
            ->setAttrib('class','form-text');

        $passwordConfirmation = new App_PasswordConfirmation();
        $form['password'] = new Zend_Form_Element_Password('password');
        $form['password']->setLabel('Password')
            ->setRequired()
            ->addValidator($passwordConfirmation)
            ->setAttrib('class', 'form-text');

        $form['confirmpassword'] = new Zend_Form_Element_Password('confirmpassword');
        $form['confirmpassword']->setLabel('Confirm Password')->setAttrib('class', 'input_box confirmpassword');
        $form['confirmpassword']->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);
        
        $form['email'] = new Zend_Form_Element_Text('email');
        $form['email']->setLabel('Email')
            ->addValidator('emailAddress', false)
            ->setAttrib('class', 'form-text')
            ->addFilter('stringTrim')
            ->setRequired();
        
        
        //@todo reCaptcha
                                
        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')
            ->setAttrib('class', 'form-submit');
                                         
        $this->addElements($form);
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array(
                            array( 'wrapperAll' => 'HtmlTag' ),
                            array( 'tag' => 'div','class'=>'clearfix form-item')
                        )
                    )
            );
        }

        $this->addDisplayGroup( array('organisation_name', 'organisation_address', 'organisation_username'),
                                'field',
                                array('legend'=>'Organisation Information')
                            );
        
        $this->addDisplayGroup(
                                array('first_name', 'middle_name', 'last_name',
                                     'admin_username', 'password', 'confirmpassword', 'email'), 
                                'field1',
                                array('legend'=>'Admin Information')
                            );
        
        // Default Field Values form
        $defValues = new Form_Wep_DefaultFieldValues();
        $defValues->load($defaults);
        $defValues->removeDecorator('form');
        $this->addSubForm($defValues , 'default_field_values');
        
        //Default Field Groups form
        $disGroup = new Form_Wep_DefaultFieldGroups();
        $disGroup->load($defaults);
        $disGroup->removeDecorator('form');
        $this->addSubForm($disGroup , 'default_field_groups');
        
        $groups = $this->getDisplayGroups();
        foreach($groups as $group){
            $group->addDecorators(array(
                array(
                        array( 'wrapperAll' => 'HtmlTag' ),
                        array( 'tag' => 'div','class'=>'default-activity-list')
                    )
            ));
        }
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}