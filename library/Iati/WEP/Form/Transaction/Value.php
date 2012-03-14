<?php

class Iati_WEP_Form_Transaction_Value extends App_Form
{
    public function init()
    {
        $element = new Iati_WEP_Activity_Elements_Transaction_Value();
        $model = new Model_Wep();
        
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
                    
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $currency = $element->getOptions('currency');
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($currency);
        
        $form['value_date'] = new Zend_Form_Element_Text('value_date');
        $form['value_date']->setLabel('Value Date')
            ->setAttrib('class' , 'datepicker' );
            
        $this->addElements($form);
    }
}