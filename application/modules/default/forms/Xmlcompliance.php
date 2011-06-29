<?php
class Form_Xmlcompliance extends Zend_Form
{

    public function init()
    {
        $xmlUrl = new Zend_Form_Element_Text('xmlUrl', array('size' => 80));
        $xmlUrl->setLabel('Xml Url')->setRequired(true)
        ->setDescription('EG, http://www.example.com/path/to/file.xml');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');

        $reset = new Zend_Form_Element_Reset('reset');
        $reset->setValue('Reset');

        $this->addElements(array($xmlUrl, $submit, $reset));
    }

    public function  loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();

        $this->getElement('submit')->setDecorators(array('ViewHelper'));
        $this->getElement('reset')->setDecorators(array('ViewHelper'));
    }

}