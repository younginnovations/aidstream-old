<?php

class Iati_WEP_Form_Result extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();
        
        $this->setAttrib('id' ,'result')
            ->setAttrib('class' , 'top-element collapsable collapsed')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $types = $model->getCodeArray('ResultType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Result Type');
        $form['type']->setAttrib('class' , 'form-select')
            ->setMultiOptions($types);
        $this->addElements($form);
    }
}