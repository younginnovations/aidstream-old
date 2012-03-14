<?php

class Iati_WEP_Form_Transaction_DisbursementChannel extends App_Form
{
    public function init()
    {
        $element = new Iati_WEP_Activity_Elements_Transaction_DisbursementChannel();
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child non-required-element')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $codes = $element->getOptions('code');
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Code')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($codes);
                    
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
        
        $this->addElements($form);
    }
}