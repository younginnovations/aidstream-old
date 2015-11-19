<?php

class Iati_Aidstream_Form_Activity_CapitalSpend extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setRequired()
            ->addValidator(new App_Validate_NumericValue())
            ->setValue($this->data['@percentage'])
            ->setAttrib('class' , 'form-text');
        
        $this->addElements($form);
        return $this;
    }
}