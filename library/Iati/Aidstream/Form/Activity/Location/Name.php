<?php

class Iati_Aidstream_Form_Activity_Location_Name extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {   
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $this->addElements($form);
        return $this;
    }
}