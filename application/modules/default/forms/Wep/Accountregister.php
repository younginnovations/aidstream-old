<?php
class Form_Wep_Accountregister extends App_Form
{
    public function add($defaultFields = '', $state = 'add')
    {
        $form = array();

        $form['organisation_name'] = new Zend_Form_Element_Text('organisation_name');
        $form['organisation_name']->setLabel('Name')
        ->setRequired()->setAttrib('class', 'form-text');

        $form['organisation_address'] = new Zend_Form_Element_Textarea('organisation_address');
        $form['organisation_address']->setLabel('Address')->setAttrib('rows', '4')
        ->setRequired()->setAttrib('class', 'form-textarea');

        $form['organisation_username'] = new Zend_Form_Element_Text('organisation_username');
        $form['organisation_username']->setLabel("Suffix <a href='#' id='suffix'>?</a>")->setRequired()
        ->setDescription('<div class="popup">Suffix is a unique text and it will be prepended to your user account. For example: If you type IATI in Suffix, your username will be IATI_admin.
        </div>')->setAttrib('class', 'form-text')
        ->setDecorators(array(
                        'ViewHelper',
                        array('Description', array('escape' => false, 'tag' => false)),
                        array('HtmlTag', array('tag' => 'dd')),
                        array('Label', array('tag' => 'dt')),
                        'Errors',
                        ));

        $form['first_name'] = new Zend_Form_Element_Text('first_name');
        $form['first_name']->setLabel('First Name')->setRequired()->setAttrib('class', 'form-text');

        $form['middle_name'] = new Zend_Form_Element_Text('middle_name');
        $form['middle_name']->setLabel('Middle Name')->setAttrib('class', 'form-text');

        $form['last_name'] = new Zend_Form_Element_Text('last_name');
        $form['last_name']->setLabel('Last Name')->setRequired()->setAttrib('class', 'form-text');

        /*$form['admin_username'] = new Zend_Form_Element_Text('admin_username');
        $form['admin_username']->setLabel('Username')->setRequired();*/

        $passwordConfirmation = new App_PasswordConfirmation();
        $form['password'] = new Zend_Form_Element_Password('password');
        $form['password']->setLabel('Password')->setRequired()->addValidator($passwordConfirmation)
        ->setAttrib('class', 'form-text');

        $form['confirmpassword'] = new Zend_Form_Element_Password('confirmpassword');
        $form['confirmpassword']->setLabel('Confirm Password')->setAttrib('class', 'input_box confirmpassword');
        $form['confirmpassword']->setRequired()
        ->setAttrib('class', 'form-text')->addValidator($passwordConfirmation);
        
        $form['email'] = new Zend_Form_Element_Text('email');
        $form['email']->setLabel('Email')
        ->addValidator('emailAddress', false)->setAttrib('class', 'form-text')
        ->addFilter('stringTrim')
        ->setRequired();
        
        $model = new Model_Wep();
        $currency = $model->getCodeArray('Currency',null,'1');
//        print_r($currency);exit;
        $selectedCurrency = $model->findIdByFieldData('Currency', $defaultFields['field_values']['currency'], '1');
//        print_r($selectedCurrency[0]);exit();
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()->setLabel('Default Currency')->addMultiOption('', 'Select anyone');
        $form['default_currency']->setValue($selectedCurrency[0]['id'])
        ->setAttrib('class', 'form-select');
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }
        
        $language = $model->getCodeArray('Language',null,'1');
        $selectedLanguage = $model->findIdByFieldData('Language', $defaultFields['field_values']['language'], '1');
//        print_r($selectedLanguage);exit;
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()->setLabel('Default Language')->addMultiOption('', 'Select anyone');
        $form['default_language']->setValue($selectedLanguage[0]['id'])->setAttrib('class', 'form-select');
        foreach($language as $key => $eachLanguage){
            $form['default_language']->addMultiOption($key, $eachLanguage);
        }
        
        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Default Reporting Organisation Name')
                                        ->setValue($defaultFields['reporting_org'])
                                        ->setRequired()->setAttrib('class', 'form-text');
        
        $form['reporting_org_ref'] = new Zend_Form_Element_Text('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Default Reporting Organisation Identifier')
                                    ->setValue($defaultFields['reporting_org_ref'])
                                    ->setAttrib('class', 'form-text');

        $form['default_hierarchy'] = new Zend_Form_Element_Text('default_hierarchy');
        $form['default_hierarchy']->setLabel('Default Hierarchy')
                                ->setValue($defaultFields['hierarchy'])->setAttrib('class', 'form-text');
        
        //@todo reCaptcha
        
                                
        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')->setAttrib('class', 'form-submit');
                                         
        $this->addElements($form);
        
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
            
            }
        $this->addElement('multiCheckbox', 'default_fields', array(
                        'disableLoadDefaultDecorators' => true,
                        'separator'    => '&nbsp;',
                        'multiOptions' => $default_fields,
                        'decorators'   => array(
                                    'ViewHelper',
                                    'Errors',
                                array('HtmlTag', array('tag' => 'p'))          
                        )
        ));
        
        $this->addDisplayGroup(array('organisation_name', 'organisation_address', 'organisation_username'), 'field',array('legend'=>'Organisation Information'));
        $this->addDisplayGroup(array('first_name', 'middle_name', 'last_name', 'password', 'confirmpassword', 'email'), 
                                        'field1',array('legend'=>'Admin Information'));
//        $this->addDisplayGroup()
        $this->addDisplayGroup(array('default_currency', 'default_language', 'default_reporting_org', 'reporting_org_ref', 'default_hierarchy'), 'field2',array('legend'=>'Default Field Values'));
       
        $this->addDisplayGroup(array('default_fields',), 'field3',array('legend'=>'Default Field Groups'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}