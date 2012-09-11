<?php

class Iati_Organisation_Form_OrganisationName extends Iati_Organisation_Form_BaseForm
{
    public function getFormDefination()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        

        $form['amount'] = new Zend_Form_Element_Text('amount');
        $form['amount']->setLabel('Amount')
            ->setRequired()
            ->addFilter(new Iati_Filter_Currency())
            ->setValue($this->data['amount'])
            ->addValidator(new App_Validate_Numeric())
            ->setAttrib('class', 'form-text');
            
        $currency = $model->getCodeArray('Currency' , '' , 1 , true);
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->addMultiOptions($currency)
            ->setValue($this->data['currency'])
            ->setAttrib('class', 'form-select');

        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Start Date')
            ->setRequired()
            ->setValue($this->data['start_date'])
            ->setAttrib('class', 'form-text datepicker');

        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('End Date')
            ->setRequired()
            ->setValue($this->data['end_date'])
            ->addValidator(new App_Validate_EndDate())
            ->setAttrib('class', 'form-text datepicker');
        
        $form['signed_date'] = new Zend_Form_Element_Text('signed_date');
        $form['signed_date']->setLabel('Contract Signed  Date')
            ->setRequired()
            ->setValue($this->data['signed_date'])
            ->setAttrib('class', 'form-text datepicker');

        $this->addElements($form);
        //$this->prepare();
        return $this;
    }
}