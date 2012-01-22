<?php
class Form_Wep_EditDefaults extends App_Form
{
    public function edit($defaults = '', $account_id = '')
    {
        $form = array();
        $model = new Model_Wep();
        
        
        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Reporting Organisation Name')
            ->setValue($defaults['field_values']['reporting_org'])
            ->setRequired()
            ->setAttrib('class', 'form-text');

        $form['reporting_org_ref'] = new Zend_Form_Element_Text('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Reporting Organisation Identifier')
            ->setRequired()
            ->setValue($defaults['field_values']['reporting_org_ref'])
            ->setAttrib('width','20px')
            ->setAttrib('class', 'form-text');
            
        $reportingOrgType = $model->getCodeArray('OrganisationType',null,'1');
        $form['reporting_org_type'] = new Zend_Form_Element_Select('reporting_org_type');
        $form['reporting_org_type']->setLabel('Reporting Organisation Type')
            ->setRequired()
            ->setValue($defaults['field_values']['reporting_org_type'])
            ->addMultiOption('','Select anyone')
            ->addMultiOptions($reportingOrgType)
            ->setAttrib('width','20px')
            ->setAttrib('class', 'form-select');
            
        $language = $model->getCodeArray('Language',null,'1');
        $form['reporting_org_lang'] = new Zend_Form_Element_Select('reporting_org_lang');
        $form['reporting_org_lang']->setLabel('Reporting Organisation Language')
            ->addMultiOption('', 'Select anyone')->setValue($defaults['field_values']['reporting_org_lang'])
            ->setAttrib('class', 'form-select')
            ->addMultiOptions($language);
        
        $currency = $model->getCodeArray('Currency',null,'1');
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()
            ->setLabel('Default Currency')
            ->addMultiOption('', 'Select anyone')
            ->setValue($defaults['field_values']['currency'])
            ->setAttrib('class', 'form-select');
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }
        
        $language = $model->getCodeArray('Language',null,'1');
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()
            ->setLabel('Default Language')
            ->addMultiOption('', 'Select anyone')
            ->setValue($defaults['field_values']['language'])
            ->setAttrib('class', 'form-select');
        foreach($language as $key => $eachLanguage){
            $form['default_language']->addMultiOption($key, $eachLanguage);
        }
        
        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setLabel('Default Hierarchy')
            ->setAttrib('class' , 'form-text')
            ->setValue($defaults['field_values']['hierarchy']);
                                    
        $form['default_collaboration_type'] = new Zend_Form_Element_Select('default_collaboration_type');
        $form['default_collaboration_type']->setLabel('Default Collaboration Type')
            ->setValue($defaults['field_values']['collaboration_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $collaborationTypes = $model->getCodeArray('CollaborationType',null,'1');
        foreach($collaborationTypes as $key => $collaborationType){
            $form['default_collaboration_type']->addMultiOption($key, $collaborationType);
        }
        
        $form['default_flow_type'] = new Zend_Form_Element_Select('default_flow_type');
        $form['default_flow_type']->setLabel('Default Flow Type')
            ->setValue($defaults['field_values']['flow_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $flowTypes = $model->getCodeArray('FlowType',null,'1');
        foreach($flowTypes as $key => $flowType){
            $form['default_flow_type']->addMultiOption($key, $flowType);
        }
        
        $form['default_finance_type'] = new Zend_Form_Element_Select('default_finance_type');
        $form['default_finance_type']->setLabel('Default Finance Type')
            ->setValue($defaults['field_values']['finance_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $financeTypes = $model->getCodeArray('FinanceType',null,'1');
        foreach($financeTypes as $key => $financeType){
            $form['default_finance_type']->addMultiOption($key, $financeType);
        }
        
        $form['default_aid_type'] = new Zend_Form_Element_Select('default_aid_type');
        $form['default_aid_type']->setLabel('Default Aid Type')
            ->setValue($defaults['field_values']['aid_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $aidTypes = $model->getCodeArray('AidType',null,'1');
        foreach($aidTypes as $key => $aidType){
            $form['default_aid_type']->addMultiOption($key, $aidType);
        }
        
        $form['default_tied_status'] = new Zend_Form_Element_Select('default_tied_status');
        $form['default_tied_status']->setLabel('Default Tied Status')
            ->setValue($defaults['field_values']['tied_status'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $tiedStatuses = $model->getCodeArray('TiedStatus',null,'1');
        foreach($tiedStatuses as $key => $tiedStatus){
            $form['default_tied_status']->addMultiOption($key, $tiedStatus);
        }
                                    
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->setDecorators( array(
                        'ViewHelper',
                        'Errors',
                        'Label',
                        array('HtmlTag',
                              array(
                                    'tag'        =>'<div>',
                                    'placement'  =>'APPEND',
                                    'class'      =>'help activity_defaults-'.$item_name
                                )
                            ),
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-element'))
                    )
            );
        }
        
        
        //@todo reCaptcha
        
                                
        $signup = new Zend_Form_Element_Submit('Save');
        $signup->setValue('save')
            ->setAttrib('class', 'form-submit');
                 
        
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All')
            ->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
                                
        $this->addElements($form);
        
        foreach($defaults['fields'] as $key=>$eachDefault){
            $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
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
                                        array('HtmlTag', array('tag' => 'p')
                                    )          
                        )
        ));
        
        $this->addDisplayGroup(array( 'reporting_org_ref', 'reporting_org_type' ,'default_reporting_org' , 'reporting_org_lang'),
                               'reporting_org_info',
                               array('legend' =>'Reporting Organisation Info')
                               );
        
        $registryInfoForm = new Form_General_RegistryInfo();
        $this->addSubForm($registryInfoForm , 'registry_info');
        
        $this->addDisplayGroup(array('default_currency', 'default_language', 'hierarchy', 'default_collaboration_type' , 'default_flow_type', 'default_finance_type' , 'default_aid_type', 'default_tied_status'),
                               'default_field_values',
                               array('legend'=>'Default Field Values')
                            );
       
        $df = $this->addDisplayGroup(array('button', 'default_fields',),
                                    'default_field_groups',
                                    array('legend'=>'Default Field Groups')
                                );
        
        $groups = $this->getDisplayGroups();
        foreach($groups as $group){
            $group->addDecorators(array(
                array('HtmlTag' , array('tag' => 'div' , 'class' => 'help activity_defaults-'. $group->getName().' legend-help' , 'placement' => 'PREPEND')),
                array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
            ));
        }
        $this->addElement($signup);
        $this->setMethod('post');
    }
}