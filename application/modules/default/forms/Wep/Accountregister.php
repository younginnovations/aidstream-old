<?php
class Form_Wep_Accountregister extends App_Form
{
    public function add($defaultFields = '', $state = 'add')
    {
        $form = array();

        $form['organisation_name'] = new Zend_Form_Element_Text('organisation_name');
        $form['organisation_name']->setLabel('Name')
        ->setRequired();

        $form['organisation_address'] = new Zend_Form_Element_Textarea('organisation_address');
        $form['organisation_address']->setLabel('Address')->setAttrib('rows', '4')
        ->setRequired();

        $form['organisation_username'] = new Zend_Form_Element_Text('organisation_username');
        $form['organisation_username']->setLabel('Suffix')->setRequired();

        $form['first_name'] = new Zend_Form_Element_Text('first_name');
        $form['first_name']->setLabel('First Name')->setRequired();

        $form['middle_name'] = new Zend_Form_Element_Text('middle_name');
        $form['middle_name']->setLabel('Middle Name');

        $form['last_name'] = new Zend_Form_Element_Text('last_name');
        $form['last_name']->setLabel('Last Name')->setRequired();

        /*$form['admin_username'] = new Zend_Form_Element_Text('admin_username');
        $form['admin_username']->setLabel('Username')->setRequired();*/

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
        
        $model = new Model_Viewcode();
        $currency = $model->getCode('Currency',null,'1');
        $selectedCurrency = $model->findIdByFieldData('Currency', $defaultFields['field_values']['currency'], '1');
//        print_r($selectedCurrency[0]);exit();
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()->setLabel('Default Currency')->addMultiOption('', 'Select anyone');
        $form['default_currency']->setValue($selectedCurrency[0]['id']);
        foreach($currency[0] as $eachCurrency){
            $form['default_currency']->addMultiOption($eachCurrency['id'], $eachCurrency['Code']);
        }
        
        $language = $model->getCode('Language',null,'1');
        $selectedLanguage = $model->findIdByFieldData('Language', $defaultFields['field_values']['language'], '1');
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()->setLabel('Default Language')->addMultiOption('', 'Select anyone');
        $form['default_language']->setValue($selectedLanguage[0]['id']);
        foreach($language[0] as $eachLanguage){
            $form['default_language']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }
        
        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Default Reporting Organisation Name')
                                ->setValue($defaultFields['reporting_org'])
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
        
        
        $this->addDisplayGroup(array('organisation_name', 'organisation_address', 'organisation_username'), 'field',array('legend'=>'Organisation Information'));
        $this->addDisplayGroup(array('first_name', 'middle_name', 'last_name', 'password', 'confirmpassword', 'email'), 
                                        'field1',array('legend'=>'Admin Information'));
//        $this->addDisplayGroup()
        $this->addDisplayGroup(array('default_currency', 'default_language', 'default_reporting_org'), 'field2',array('legend'=>'Default Field Values'));
       
        $this->addDisplayGroup(array('default_fields',), 'field3',array('legend'=>'Default Field Groups'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}