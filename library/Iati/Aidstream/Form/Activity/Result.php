<?php

class Iati_Aidstream_Form_Activity_Result extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $type = $model->getCodeArray('ResultType', null, '1',true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Type')
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select')
            ->setRequired()    
            ->setMultioptions($type);

        $form['aggregation_status'] = new Zend_Form_Element_Select('aggregation_status');
        $form['aggregation_status']->setLabel('Aggregation Status')    
            ->setAttribs(array('class' => 'form-select')) 
            ->setValue($this->data['@aggregation_status'])
            ->setMultiOptions(array(''=>'Select one of the following option:','true'=>'True','false'=>'False'));             

        $this->addElements($form);
        return $this;
    }
}