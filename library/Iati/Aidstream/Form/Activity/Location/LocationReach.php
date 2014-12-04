<?php

class Iati_Aidstream_Form_Activity_Location_LocationReach extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $locationReach = $model->getCodeArray('GeographicLocationReach', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Code')  
            ->setValue($this->data['@code'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($locationReach);

        $this->addElements($form);
        return $this;
    }
}