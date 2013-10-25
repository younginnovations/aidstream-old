<?php

class Iati_Aidstream_Form_Activity_Location extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setValue($this->data['@percentage'])
            ->addValidator(new App_Validate_NumericValue())    
            ->setAttrib('class' , 'form-text');
        
        $this->addElements($form);
        return $this;
    }
}