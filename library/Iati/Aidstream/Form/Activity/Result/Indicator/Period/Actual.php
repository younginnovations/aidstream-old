<?php

class Iati_Aidstream_Form_Activity_Result_Indicator_Period_Actual extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['value'] = new Zend_Form_Element_Text('value');
        $form['value']->setLabel('Value')               
            ->addValidator(new App_Validate_NumericValue())
            ->setAttribs(array('class' => 'currency form-text'))                 
            ->setRequired()
            ->setValue($this->data['@value']);

        $this->addElements($form);
        return $this;
    }
}