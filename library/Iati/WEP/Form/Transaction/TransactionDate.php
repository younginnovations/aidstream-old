<?php

class Iati_WEP_Form_Transaction_TransactionDate extends Iati_Form
{
    public function init()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $form['iso_date'] = new Zend_Form_Element_Text('iso_date');
        $form['iso_date']->setLabel('Date')
            ->setAttrib('class' , 'datepicker' );
            
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
            
        $this->addElements($form);
    }
}