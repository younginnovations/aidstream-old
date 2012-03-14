<?php

class Iati_WEP_Form_Transaction extends App_Form
{
    public function init()
    {
        $this->setAttrib('id' ,'transaction')
            ->setAttrib('class' , 'top-element collapsable collapsed')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        
        $form['ref'] = new Zend_Form_Element_Text('ref', array(
                'label'      => 'Ref',
                'filters'    => array('StringTrim', 'StringToLower')
            ));
        $this->addElements($form);
    }
}