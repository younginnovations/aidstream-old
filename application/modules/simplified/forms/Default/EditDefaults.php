<?php
class Simplified_Form_Default_EditDefaults extends App_Form
{
    public function edit($defaults = '', $account_id = '')
    {
        $form = array();
        $model = new Model_Wep();
                         
        // Reporting organisation form
        $repOrg = new Simplified_Form_Default_ReportingOrganisation();
        $repOrg->load($defaults);
        $repOrg->removeDecorator('form');
        $this->addSubForm($repOrg , 'reporting_organisation');
        
        // Registry Info and Publishing Type form
        $registryInfoForm = new Form_General_RegistryInfo();
        $registryInfoForm->removeDecorator("form");
        $this->addSubForm($registryInfoForm , 'registry_info');
        
        // Default Field Values form
        $defValues = new Simplified_Form_Default_DefaultFieldValues();
        $defValues->load($defaults);
        $defValues->removeDecorator('form');
        $this->addSubForm($defValues , 'default_field_values');
        
        
        $signup = new Zend_Form_Element_Submit('Save');
        $signup->setValue('save')
            ->setAttrib('class', 'form-submit');
        $this->addElement($signup);
        $this->setMethod('post');
    }
}
