<?php

class Iati_Aidstream_Form_Activity_ContactInfo_Department extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $this->addElements($form);
        return $this;
    }
}