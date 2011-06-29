<?php
class Form_Wep_ReportingOrganisation extends App_Form
{

    public function add($state = 'add', $activity_id = '', $account_id = ''){
        $form = array();
        $this->setName('reporting_org');

        $model = new Model_Viewcode();

        $organisationIdentifier = $model->getCode('OrganisationIdentifier', null, '1');
        $organisationType = $model->getCode('OrganisationType', null, '1');
//        print_r($organisationType);exit();
        $language = $model->getCode('Language',null,'1');
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        $this->setName('iati_reporting_org');
        
        $form['attr_iati_reporting_org_xmllang'] = new Zend_Form_Element_Select('attr_iati_reporting_org_xmllang');
        $form['attr_iati_reporting_org_xmllang']->setLabel('Language')
        ->addMultiOption('', 'Select anyone')->setValue($default['language']);
        foreach($language[0] as $eachLanguage){
            $form['attr_iati_reporting_org_xmllang']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }

         
        $form['attr_iati_reporting_org_ref'] = new Zend_Form_Element_Select('attr_iati_reporting_org_ref');
        $form['attr_iati_reporting_org_ref']->setLabel('Identifier')->addMultiOption('', 'Select anyone');
        foreach($organisationIdentifier[0] as $eachIdentifier){
            $form['attr_iati_reporting_org_ref']->addMultiOption($eachIdentifier['id'], $eachIdentifier['Code']);
        }

        $form['activity_id'] = new Zend_Form_Element_Hidden('activity_id');
        $form['activity_id']->setValue($activity_id)->setAttrib('class','hidden-field');
        
        $form['form_name'] = new Zend_Form_Element_Hidden('form_name');
        $form['form_name']->setValue('Reporting Organisation')->setAttrib('class','hidden-field');
        
        $form['table'] = new Zend_Form_Element_Hidden('table');
        $form['table']->setValue('iati_reporting_org')
        ->setAttrib('class','hidden-field');
        
        $form['attr_iati_reporting_org_type'] = new Zend_Form_Element_Select('attr_iati_reporting_org_type');
        $form['attr_iati_reporting_org_type']->setLabel('Organisation Type')->addMultiOption('', 'Select anyone');
        foreach($organisationType[0] as $eachType){
            $form['attr_iati_reporting_org_type']->addMultiOption($eachType['id'], $eachType['Code']);
        }

        $form['iati_reporting_org_text'] = new Zend_Form_Element_Text('iati_reporting_org_text');
        $form['iati_reporting_org_text']->setLabel('Name')->setValue($default['reporting_organisation'])
        ->setRequired();

        $form['save'] = new Zend_Form_Element_Submit('save');
        $form['save']->setValue('Save')->setAttrib('class','ajax_save');
        
        $this->addElements($form);
        $this->addDisplayGroup(array('attr_iati_reporting_org_xmllang', 'attr_iati_reporting_org_ref', 
                                    'attr_iati_reporting_org_type', 'iati_reporting_org_text', 'save'),
                                     'field',array('legend'=>'Reporting Organisaton'));
       
        
        //
        $this->setMethod('post');
        
    }
}