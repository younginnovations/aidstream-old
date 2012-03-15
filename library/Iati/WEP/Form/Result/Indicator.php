<?php

class Iati_WEP_Form_Result_Indicator extends Iati_Form
{
    public function init()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $form['measure'] = new Zend_Form_Element_Textarea('measure');
        $form['measure']->setLabel('Measure')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20' , 'class' => 'form-text'));
        
        $this->addElements($form);
    }
}