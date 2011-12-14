<?php
class Form_Wep_Accountregister extends App_Form
{
    public function add($defaultFields = '', $state = 'add')
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
            ->setDescription('<div class="popup">This name will be prefixed to all the usernames created for the organisation (eg if the prefix is ABC then the username will be ABC_admin for admin user.)</div>')
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
        
        $currency = $model->getCodeArray('Currency',null,'1');
        $selectedCurrency = $model->findIdByFieldData('Currency', $defaultFields['field_values']['currency'], '1');
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()
            ->setLabel('Currency')
            ->setValue($selectedCurrency[0]['id'])
            ->addMultiOption('', 'Select anyone')
            ->setAttrib('class', 'form-select');
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }
        
        $language = $model->getCodeArray('Language',null,'1');
        $selectedLanguage = $model->findIdByFieldData('Language', $defaultFields['field_values']['language'], '1');
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()
            ->setLabel('Language')
            ->setValue($selectedLanguage[0]['id'])
            ->addMultiOption('', 'Select anyone')
            ->setAttrib('class', 'form-select');
        foreach($language as $key => $eachLanguage){
            $form['default_language']->addMultiOption($key, $eachLanguage);
        }
        
        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Reporting Org Name')
            ->setValue($defaultFields['reporting_org'])
            ->setRequired()->setAttrib('class', 'form-text');
        
        $form['reporting_org_ref'] = new Zend_Form_Element_Text('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Reporting Org Identifier')
            ->setValue($defaultFields['reporting_org_ref'])
            ->setAttrib('class', 'form-text')->setRequired();
            
        $reportingOrgType = $model->getCodeArray('OrganisationType',null,'1');
        $form['reporting_org_type'] = new Zend_Form_Element_Select('reporting_org_type');
        $form['reporting_org_type']->setLabel('Reporting Organisation Type')
            ->setRequired()
            ->setValue($defaultFields['reporting_org_type'])
            ->addMultiOption('','Select anyone')
            ->addMultiOptions($reportingOrgType)
            ->setAttrib('width','20px')
            ->setAttrib('class', 'form-select');

        $form['default_hierarchy'] = new Zend_Form_Element_Text('default_hierarchy');
        $form['default_hierarchy']->setLabel('Hierarchy')
            ->setValue($defaultFields['hierarchy'])
            ->setAttrib('class', 'form-text');
                                
        $form['default_collaboration_type'] = new Zend_Form_Element_Select('default_collaboration_type');
        $form['default_collaboration_type']->setLabel('Default Collaboration Type')
            ->setValue($defaults['field_values']['default_collaboration_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $collaborationTypes = $model->getCodeArray('CollaborationType',null,'1');
        foreach($collaborationTypes as $key => $collaborationType){
            $form['default_collaboration_type']->addMultiOption($key, $collaborationType);
        }
        
        $form['default_flow_type'] = new Zend_Form_Element_Select('default_flow_type');
        $form['default_flow_type']->setLabel('Default Flow Type')
            ->setValue($defaults['field_values']['default_flow_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $flowTypes = $model->getCodeArray('FlowType',null,'1');
        foreach($flowTypes as $key => $flowType){
            $form['default_flow_type']->addMultiOption($key, $flowType);
        }
        
        $form['default_finance_type'] = new Zend_Form_Element_Select('default_finance_type');
        $form['default_finance_type']->setLabel('Default Finance Type')
            ->setValue($defaults['field_values']['default_finance_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $financeTypes = $model->getCodeArray('FinanceType',null,'1');
        foreach($financeTypes as $key => $financeType){
            $form['default_finance_type']->addMultiOption($key, $financeType);
        }
        
        $form['default_aid_type'] = new Zend_Form_Element_Select('default_aid_type');
        $form['default_aid_type']->setLabel('Default Aid Type')
            ->setValue($defaults['field_values']['default_aid_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $aidTypes = $model->getCodeArray('AidType',null,'1');
        foreach($aidTypes as $key => $aidType){
            $form['default_aid_type']->addMultiOption($key, $aidType);
        }
        
        $form['default_tied_status'] = new Zend_Form_Element_Select('default_tied_status');
        $form['default_tied_status']->setLabel('Default Tied Status')
            ->setValue($defaults['field_values']['default_tied_status'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $tiedStatuses = $model->getCodeArray('TiedStatus',null,'1');
        foreach($tiedStatuses as $key => $tiedStatus){
            $form['default_tied_status']->addMultiOption($key, $tiedStatus);
        }
        
        //@todo reCaptcha
                                
        $signup = new Zend_Form_Element_Submit('Signup');
        $signup->setValue('signup')
            ->setAttrib('class', 'form-submit');
        
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All')
            ->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
                                         
        $this->addElements($form);
        
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
            
            }
        $defultChecked = array('title','description','activity_status','activity_date','participating_org','recipient_country','sector','budget','transaction');
        $this->addElement('multiCheckbox', 'default_fields', array(
            'disableLoadDefaultDecorators' => true,
            'separator'    => '&nbsp;',
            'value'      => $defultChecked,
            'multiOptions' => $default_fields,
            'decorators'   => array(
                'ViewHelper',
                'Errors',
                array('HtmlTag', array('tag' => 'p'))          
            )
        ));
        
        $this->addDisplayGroup( array('organisation_name', 'organisation_address', 'organisation_username'),
                                'field',
                                array('legend'=>'Organisation Information')
                            );
        
        $this->addDisplayGroup( array('first_name', 'middle_name', 'last_name', 'admin_username', 'password', 'confirmpassword', 'email'), 
                                'field1',
                                array('legend'=>'Admin Information')
                            );
        
        $this->addDisplayGroup(array( 'reporting_org_ref', 'reporting_org_type' ,'default_reporting_org'),
                               'reporting_org',
                               array('legend' =>'Reporting Organisaiton Info')
                               );
        
        $registryInfoForm = new Form_General_RegistryInfo();
        $this->addSubForm($registryInfoForm , 'registry_info');
        
        $this->addDisplayGroup( array('default_currency', 'default_language', 'default_hierarchy' , 'default_collaboration_type' , 'default_flow_type' , 'default_finance_type' , 'default_aid_type' , 'default_tied_status'),
                                'field2',
                                array('legend'=>'Default Field Values')
                            );
       
        $this->addDisplayGroup( array('button','default_fields',),
                                'field3',
                                array('legend'=>'Default Field Groups')
                            );
        
        $groups = $this->getDisplayGroups();
        foreach($groups as $group){
            $group->addDecorators(array(
                array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        }
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}