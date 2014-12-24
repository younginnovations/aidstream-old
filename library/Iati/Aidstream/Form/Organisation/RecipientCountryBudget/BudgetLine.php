<?php

class Iati_Aidstream_Form_Organisation_RecipientCountryBudget_BudgetLine extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Reference')
           ->setValue($this->data['@ref'])
           ->setAttrib('class','form-text');
           
        $this->addElements($form);
        return $this;
    }
}