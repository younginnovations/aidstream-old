<?php

class Iati_Aidstream_Form_Activity_Result_Indicator_Period extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $this->addElements($form);
        return $this;

    }

}