<?php

/**
 * Description of Validateurl
 *
 * @author Bibek Shrestha <bibekshrestha@gmail.com>
 */
class Form_Validateurl extends Zend_Form
{
    public function init()
    {
        $xmlUrl = new Zend_Form_Element_Text('xmlUrl', array('size' => 80));
        $xmlUrl->setLabel('Xml Url')->setDescription('EG, http://www.example.com/path/to/file.xml');

        $iativalidator = new Iati_Iatischema_Validator();
        $schemaVersions = new Zend_Form_Element_Select('schemaVersion');
        $schemaVersions->setLabel("Select Schema");
        foreach ($iativalidator->getAvailableSchemaVersions() as $version) {
            $schemaVersions->addMultiOption($version, $version);
        }
        
        

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Validate');

        $reset = new Zend_Form_Element_Reset('reset');
        $reset->setValue('Reset');

        $this->addElements(array($schemaVersions, $xmlUrl, $submit, $reset));
    }

    public function  loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();

        $this->getElement('submit')->setDecorators(array('ViewHelper'));
        $this->getElement('reset')->setDecorators(array('ViewHelper'));
    }
}
