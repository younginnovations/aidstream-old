<?php

class Iati_Aidstream_Form_Activity_Location extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['ref'] = new Zend_Form_Element_Textarea('ref');
        $form['ref']->setLabel('Reference')  
            ->setValue($this->data['@ref'])   
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $this->addElements($form);
        return $this;
    }
}