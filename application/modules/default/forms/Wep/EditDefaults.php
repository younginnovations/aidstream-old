<?php
class Form_Wep_EditDefaults extends App_Form
{
    public function edit($defaults = '', $account_id = '')
    {
        $form = array();
        $model = new Model_Wep();
                         
        // Reporting organisation form
        $repOrg = new Form_Wep_ReportingOrganisation();
        $repOrg->load($defaults);
        $repOrg->removeDecorator('form');
        $this->addSubForm($repOrg , 'reporting_organisation');
        
        // Registry Info and Publishing Type form
        $registryInfoForm = new Form_General_RegistryInfo();
        $registryInfoForm->removeDecorator("form");
        $this->addSubForm($registryInfoForm , 'registry_info');
        
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
        
        
        $signup = new Zend_Form_Element_Submit('Save');
        $signup->setValue('save')
            ->setAttrib('class', 'form-submit');
        $this->addElement($signup);
        $this->setMethod('post');
    }
}