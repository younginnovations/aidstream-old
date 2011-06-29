<?php
class Form_Xmlcompliance_Xmlfile extends Zend_Form
{

    public function init()
    {
        $xmlFile = new Zend_Form_Element_File('xmlFile');
        $xmlFile->setLabel('XML File');
        $xmlFile->addValidator('Extension',false, 'xml');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');

        $reset = new Zend_Form_Element_Reset('reset');
        $reset->setValue('Reset');

        $this->addElements(array($xmlFile, $submit, $reset));
    }

    public function  loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();

        $this->getElement('submit')->setDecorators(array('ViewHelper'));
        $this->getElement('reset')->setDecorators(array('ViewHelper'));
    }

}