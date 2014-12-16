<?php

class Iati_Aidstream_Form_Organisation_RecipientOrgBudget_RecipientOrg extends Iati_Core_BaseForm
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
        
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Ref')
            ->setValue($this->data['@ref'])
            ->setRequired()
            ->setAttrib('class' , 'form-text');


        $this->addElements($form);
        return $this;
    }
}