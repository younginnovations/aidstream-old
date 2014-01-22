<?php
class Form_Admin_Xml extends App_Form
{
    public function init()
    {
        $this->setName('xml_form');
        $this->setMethod('post');
        $form = array();

        $form['files'] = new Zend_Form_Element_Hidden('files');
        $form['validate'] = new Zend_Form_Element_Button('validate');
        $form['validate']->setAttrib( 'class' , "form-submit")
            ->setLabel('Validate');
        $this->addElements($form);
    }
}