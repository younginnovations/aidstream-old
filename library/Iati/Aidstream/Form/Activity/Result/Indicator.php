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
        
        $indicatorMeasures = $model->getCodeArray('IndicatorMeasure', null, '1' , true);
        $form['measure'] = new Zend_Form_Element_Select('measure');
        $form['measure']->setLabel('Measure')
            ->setAttrib('class' , 'form-select')
            ->setValue($this->data['@measure']) 
            ->setRequired()
            ->setMultioptions($indicatorMeasures);

        $form['ascending'] = new Zend_Form_Element_Select('ascending');
        $form['ascending']->setLabel('Ascending')   
            ->setAttribs(array('class' => 'form-select'))
            ->setValue($this->data['@ascending'])
            ->setMultiOptions(array(''=>'Select one of the following option:','1'=>'True','0'=>'False'));

        $this->addElements($form);
        return $this;

    }

}