<?php

class Iati_Aidstream_Form_Activity_ContactInfo_MailingAddress extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Address')  
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));

        $this->addElements($form);
        return $this;
    }
}