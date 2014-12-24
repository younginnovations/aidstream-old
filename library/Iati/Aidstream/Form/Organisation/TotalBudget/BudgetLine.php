<?php

class Iati_Aidstream_Form_Organisation_TotalBudget_BudgetLine extends Iati_Core_BaseForm
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
            ->setAttribs(array('class' => 'form-text'))
            ->setValue($this->data['@ref']);

        $this->addElements($form);
        return $this;
    }
}