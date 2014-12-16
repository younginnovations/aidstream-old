<?php

class Iati_Aidstream_Form_Activity_ActivityDate extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['iso_date'] = new Zend_Form_Element_Text('iso_date');
        $form['iso_date']->setLabel('Date')
            ->setRequired()
            ->setValue($this->data['@iso_date'])
            ->setAttrib('class' , 'datepicker' );
        
        $activityDateType = $model->getCodeArray('ActivityDateType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Activity Date Type')  
            ->setValue($this->data['@type'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($activityDateType);

        $this->addElements($form);
        return $this;
    }
}