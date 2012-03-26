<?php

class Iati_WEP_Form_Transaction extends Iati_Form
{
    public function init()
    {
        $this->setAttrib('id' ,'transaction')
            ->setAttrib('class' , 'top-element collapsable')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Ref')
            ->addfilters(array('StringTrim' , 'StringToLower'))
            ->setAttribs(array('class' => 'form-text'))
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-ref' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
    }
}