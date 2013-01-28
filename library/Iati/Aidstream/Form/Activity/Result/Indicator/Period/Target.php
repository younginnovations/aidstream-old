<?php

class Iati_Aidstream_Form_Activity_Result_Indicator_Period_Target extends Iati_Core_BaseForm
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
            ->addFilter(new Iati_Filter_Currency()) 
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@value']);

        $this->addElements($form);
        return $this;
    }
}