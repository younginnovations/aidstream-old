<?php

class Iati_Aidstream_Form_Activity_PlannedDisbursement_Value extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);


        $form['value_date'] = new Zend_Form_Element_Text('value_date');
        $form['value_date']->setLabel('Value Date')
            ->setValue($this->data['@value_date'])
            ->setRequired()
            ->setAttrib('class' , 'datepicker' );
        $form['text'] = new Zend_Form_Element_Text('text');
        $form['text']->setLabel('Amount')
           ->setRequired()
           ->setValue($this->data['text'])
           ->setAttrib('class','form-text');
    
        $currency = $model->getCodeArray('Currency', null, '1' , true);
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->setValue($this->data['@currency'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($currency);

        $this->addElements($form);
        return $this;
    }
}