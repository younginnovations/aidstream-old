<?php
class Form_Wep_IatiActivity extends App_Form
{
    public function add($status = "add", $account_id = '')
    {
        $form = array();

        $model = new Model_Wep();
        $language = $model->getCodeArray('Language',null,'1');
        $currency = $model->getCodeArray('Currency', null, '1');

        if($status != 'edit'){
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        }
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')->setAttrib('class', 'form-select')->addMultiOption('', 'Select anyone');
        if($status != 'edit'){
            $form['xml_lang']->setValue($default['language']);
        }
        
        foreach($language as $key => $eachLanguage){
            $form['xml_lang']->addMultiOption($key, $eachLanguage);
        }
         
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setLabel('Default Currency')
        ->setAttrib('class', 'form-select')->addMultiOption('', 'Select anyone');
         if($status != 'edit'){
            $form['default_currency']->setValue($default['currency']);
        }
        
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }

        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setAttrib('class', 'form-text')->setLabel('Hierarchy');

        $this->addElements($form);

        $this->addDisplayGroup(array('xml_lang', 'default_currency', 'hierarchy'), 
                                    'field1',array('legend'=>'Activity'));
        
        $form1 = new Form_Wep_ReportingOrganisation();
        $form1->add('add', $account_id);
        
        $iati_identifier = new Zend_Form_Element_Text('iati_identifier_text');
        $iati_identifier->setLabel('Iati Identifier')->setAttrib('class', 'form-text')->setRequired();
        
        $this->addSubForm($form1, 'Reporting Organisation');
        $this->addElement($iati_identifier);
        
        
        $this->addDisplayGroup(array('iati_identifier_text'), 'field',array('legend'=>'Iati Identifier'));
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('Save')->setAttrib('class','form-submit');
        $this->addElement($save);
        $this->setMethod('post');
    }
}
