<?php
class Form_Wep_ReportingOrganisation extends App_Form
{

    public function add($state = 'add', $account_id = ''){
        $form = array();
        $model = new Model_Viewcode();

        $organisationIdentifier = $model->getCode('OrganisationIdentifier', null, '1');
        $organisationType = $model->getCode('OrganisationType', null, '1');
//        print_r($organisationType);exit();
        $language = $model->getCode('Language',null,'1');
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        $form['reporting_org_xmllang'] = new Zend_Form_Element_Select('reporting_org_xmllang');
        $form['reporting_org_xmllang']->setLabel('Language')
        ->addMultiOption('', 'Select anyone')->setValue($default['language'])
        ->setAttrib('class', 'form-select');
        foreach($language[0] as $eachLanguage){
            $form['reporting_org_xmllang']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }

         
        $form['reporting_org_ref'] = new Zend_Form_Element_Select('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Identifier')->addMultiOption('', 'Select anyone')
        ->setRequired()->setAttrib('class', 'form-select');
        foreach($organisationIdentifier[0] as $eachIdentifier){
            $form['reporting_org_ref']->addMultiOption($eachIdentifier['id'], $eachIdentifier['Code']);
        }

        $form['reporting_org_type'] = new Zend_Form_Element_Select('reporting_org_type');
        $form['reporting_org_type']->setLabel('Organisation Type')
        ->addMultiOption('', 'Select anyone')->setAttrib('class', 'form-select');
        foreach($organisationType[0] as $eachType){
            $form['reporting_org_type']->addMultiOption($eachType['id'], $eachType['Code']);
        }

        $form['reporting_org_text'] = new Zend_Form_Element_Text('reporting_org_text');
        $form['reporting_org_text']->setLabel('Name')->setValue($default['reporting_org'])
        ->setRequired()->setAttrib('class', 'form-text');

        $this->addElements($form);
        $this->addDisplayGroup(array('reporting_org_xmllang', 'reporting_org_ref', 
                                    'reporting_org_type', 'reporting_org_text'),
                                     'field',array('legend'=>'Reporting Organisaton'));
       

//        $this->setMethod('post');
        
    }
}