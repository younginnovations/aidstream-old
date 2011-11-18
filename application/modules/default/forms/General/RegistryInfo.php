<?php

class Form_General_RegistryInfo extends App_Form
{
    public function init()
    {
        $form['publisher_id'] = new Zend_Form_Element_Text('publisher_id');
        $form['publisher_id']->setLabel('Publisher Id')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter the Publisher ID');
        
        $form['api_key'] = new Zend_Form_Element_Text('api_key');
        $form['api_key']->setLabel('API Key')
            ->setRequired()
            ->setAttrib('class', 'form-text')
            ->addErrorMessage('Please Enter an API key');
        
        $form['publishing_type'] = new Zend_Form_Element_Radio('publishing_type');
        $form['publishing_type']->setLabel('Publishing Type')
            ->setRequired()
            ->setValue(0)
            ->addMultiOptions(array('Unsegmented' , 'Segmented'));
        
        $this->addElements($form);
        
        $this->addDisplayGroup( array('publishing_type'),
                                'publishing_info',
                                array('legend' => 'Publishing type')
                            );
        
        $this->addDisplayGroup(
                                array('publisher_id' , 'api_key'),
                                'registry_info',
                                array('legend' => 'Registry Info')
                            );
        
    }
}