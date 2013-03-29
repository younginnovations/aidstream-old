<?php

class Iati_Aidstream_Form_Activity_PlannedDisbursement extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);  

        $form['updated'] = new Zend_Form_Element_Text('updated');
        $form['updated']->setLabel('Updated Date')
            ->setValue($this->data['@updated'])
            ->setAttrib('class' , 'datepicker' );

        $this->addElements($form);
        return $this;
    }
}