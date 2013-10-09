<?php

class Iati_Aidstream_Form_Activity_ContactInfo extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['type'] = new Zend_Form_Element_Text('type');
        $form['type']->setLabel('Type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-text'); 

        $this->addElements($form);
        return $this;
    }
}