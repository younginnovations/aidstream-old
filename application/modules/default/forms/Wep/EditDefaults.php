<?php
class Form_Wep_EditDefaults extends App_Form
{
    public function edit($defaults = '', $account_id = '')
    {
        $form = array();
        $model = new Model_Wep();
        $currency = $model->getCode('Currency',null,'1');
//        $selectedCurrency = $model->findIdByFieldData('Currency', $defaultFields['field_values']['currency'], '1');
//        print_r($selectedCurrency[0]);exit();
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()->setLabel('Default Currency')->addMultiOption('', 'Select anyone');
        $form['default_currency']->setValue($defaults['field_values']['currency']);
        foreach($currency[0] as $eachCurrency){
            $form['default_currency']->addMultiOption($eachCurrency['id'], $eachCurrency['Code']);
        }
        
        $language = $model->getCode('Language',null,'1');
//        $selectedLanguage = $model->findIdByFieldData('Language', $defaultFields['field_values']['language'], '1');
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()->setLabel('Default Language')->addMultiOption('', 'Select anyone');
        $form['default_language']->setValue($defaults['field_values']['language']);
        foreach($language[0] as $eachLanguage){
            $form['default_language']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }
        
        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Default Reporting Organisation Name')
                                ->setValue($defaults['field_values']['reporting_org'])
                                ->setRequired();
        
        $form['default_fields'] = new Zend_Form_Element_MultiCheckbox('default_fields');
        foreach($defaults['fields'] as $key=>$eachDefault){
            $form['default_fields']->addMultiOption($key, ucwords(str_replace("_", " ", $key)));
            if($eachDefault == '1'){
                $checked[] = $key;
            }
        }
        $form['default_fields']->setValue($checked);
        
        //@todo reCaptcha
        
                                
        $signup = new Zend_Form_Element_Submit('Save');
                                         $signup->setValue('save')->setAttrib('id', 'Submit');
                                         
        $this->addElements($form);
        $this->addDisplayGroup(array('default_currency', 'default_language', 'default_reporting_org'), 'field2',array('legend'=>'Default Field Values'));
       
        $this->addDisplayGroup(array('default_fields',), 'field3',array('legend'=>'Default Field Groups'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }
}