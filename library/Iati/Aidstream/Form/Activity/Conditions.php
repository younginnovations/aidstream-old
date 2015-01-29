<?php

class Iati_Aidstream_Form_Activity_Conditions extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['attached'] = new Zend_Form_Element_Select('attached');
        $form['attached']->setLabel('Conditions Attached')  
            ->setValue($this->data['@attached']) 
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions(array(''=>'Select one of the following option:','0' => 'No', '1' => 'Yes'));

        $this->addElements($form);
        return $this;
    }
}