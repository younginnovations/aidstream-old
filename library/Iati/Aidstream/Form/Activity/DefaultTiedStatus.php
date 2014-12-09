<?php

class Iati_Aidstream_Form_Activity_DefaultTiedStatus extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $tiedStatusCode = $model->getCodeArray('TiedStatus', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Tied Status Code')  
            ->setValue($this->data['@code']) 
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($tiedStatusCode);

        $this->addElements($form);
        return $this;
    }
}