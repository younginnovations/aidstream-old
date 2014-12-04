<?php

class Iati_Aidstream_Form_Activity_Location_FeatureDesignation extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {

        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $locationType = $model->getCodeArray('LocationType', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Code')  
            ->setValue($this->data['@code'])
            ->setRequired()    
            ->setAttrib('class', 'form-select')
            ->setMultioptions($locationType);

        $this->addElements($form);
        return $this;
    }
}