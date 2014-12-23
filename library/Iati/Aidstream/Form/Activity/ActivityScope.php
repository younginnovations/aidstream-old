<?php

class Iati_Aidstream_Form_Activity_ActivityScope extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $activityStatus = $model->getCodeArray('ActivityScope', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Activity Scope')
            ->setRequired()  
            ->setValue($this->data['@code'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($activityStatus);

        $this->addElements($form);
        return $this;
    }
}