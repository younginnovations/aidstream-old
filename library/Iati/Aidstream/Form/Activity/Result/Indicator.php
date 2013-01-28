<?php

class Iati_Aidstream_Form_Activity_Result_Indicator extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['measure'] = new Zend_Form_Element_Text('measure');
        $form['measure']->setLabel('Measure')   
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->setValue($this->data['@measure']);

        $form['ascending'] = new Zend_Form_Element_Text('ascending');
        $form['ascending']->setLabel('Ascending')   
            ->setAttribs(array('class' => 'form-text'))
            ->setValue($this->data['@ascending']);

        $this->addElements($form);
        return $this;

    }

}