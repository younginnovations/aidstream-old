<?php

class Iati_Aidstream_Form_Activity_ContactInfo_Department extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['text'] = new Zend_Form_Element_Text('text');
        $form['text']->setLabel('Text')  
            ->setValue($this->data['text'])
            ->setAttrib('class' , 'form-text');    

        $this->addElements($form);
        return $this;
    }
}