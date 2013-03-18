<?php

class Iati_Aidstream_Form_Activity_Result_Indicator_Baseline extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['year'] = new Zend_Form_Element_Text('year');
        $form['year']->setLabel('Year')  
            ->setAttribs(array('class' => 'form-text'))
            ->setRequired()
            ->addValidator('regex', false,array('/^[0-9]{4}/','messages'=>array('regexNotMatch'=>'Incorrect Year')))
            ->setValue($this->data['@year']);

        $form['value'] = new Zend_Form_Element_Text('value');
        $form['value']->setLabel('Value')   
            ->setRequired()
            ->addValidator(new App_Validate_NumericValue())
            ->setAttribs(array('class' => 'form-text currency'))  
            ->setValue($this->data['@value']);

        $this->addElements($form);
        return $this;

    }

}