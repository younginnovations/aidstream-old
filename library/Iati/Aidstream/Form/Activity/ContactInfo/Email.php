<?php

class Iati_Aidstream_Form_Activity_ContactInfo_Email extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Email Address')  
            ->setValue($this->data['text'])
            ->addValidator(new Zend_Validate_EmailAddress)    
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));

        $this->addElements($form);
        return $this;
    }
}
