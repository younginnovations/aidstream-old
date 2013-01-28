<?php

class Iati_Aidstream_Form_Activity_Result extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['type'] = new Zend_Form_Element_Text('type');
        $form['type']->setLabel('Type')  
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@type']);

        $form['aggregation-status'] = new Zend_Form_Element_Select('aggregation-status');
        $form['aggregation-status']->setLabel('Aggregation Status')    
            ->setAttribs(array('class' => 'form-text'))    
            ->setMultiOptions(array('true'=>'true','false'=>'false'))  
            ->setValue($this->data['@aggregation-status']);

        $this->addElements($form);
        return $this;
    }
}