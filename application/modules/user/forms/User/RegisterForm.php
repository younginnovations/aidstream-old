<?php

class User_Form_User_RegisterForm extends App_Form
{

    public function init()
    {
        $this->setName('Register');
        $this->setMethod('post');
        
        $orgname = new Zend_Form_Element_Text('org_name');
        $orgname->setLabel('Organisation Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $orgaddress = new Zend_Form_Element_Text('org_address');
        $orgaddress->setLabel('Organisation Address')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $firstname = new Zend_Form_Element_Text('first_name');
        $firstname->setLabel('First Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $middlename = new Zend_Form_Element_Text('middle_name');
        $middlename->setLabel('middle Name')
            ->setAttrib('class', 'form-text');
            
        $lastname = new Zend_Form_Element_Text('last_name');
        $lastname->setLabel('last Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');


        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')->setRequired()->addValidator('emailAddress', false)
            ->addValidator('Db_NoRecordExists', false, array('table' => 'user',
                'field' => 'email'))
            ->setAttrib('class', 'form-text');


        $passwordConfirmation = new App_Validate_PasswordConfirmation();

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);


        $confirmPassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmPassword->setLabel('Confirm Password')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addValidator($passwordConfirmation);

        $publisherId = new Zend_Form_Element_Text('publisher_id');
        $publisherId->setLabel('Publisher Id')
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter the Publisher ID');
        
        $apiKey = new Zend_Form_Element_Text('api_key');
        $apiKey->setLabel('API Key')
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter an API key');

        $this->addElements(array($orgname, $orgaddress, $firstname, $middlename, $lastname, $email, $password, $confirmPassword, $publisherId, $apiKey));
        
        $this->addDisplayGroup(
                               array('org_name' , 'org_address'),
                               'organisation_info',
                               array('legend' => 'Organisation Info')
                           );
        
        $this->addDisplayGroup(
                               array('first_name' , 'middle_name' , 'last_name' , 'email', 'password', 'confirmpassword'),
                               'user_info',
                               array('legend' => 'User Info')
                           );
        $this->addDisplayGroup(
                                array('publisher_id' , 'api_key'),
                                'register_registry_info',
                                array('legend' => 'Registry Info')
                            );
        
        $groups = $this->getDisplayGroups();
        foreach($this->getDisplayGroups() as $group){
            $group->addDecorators(array(
                array(
                      array( 'wrapperAll' => 'HtmlTag' ),
                      array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        }
        $create = new Zend_Form_Element_Submit('create_new_account');
        $create->setLabel('Sign Up');
        $this->addElement($create);
    }

}