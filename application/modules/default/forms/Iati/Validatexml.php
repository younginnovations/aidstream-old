<?php
class Form_Iati_Validatexml extends Zend_Form
{
    public function init()
    {
        $this->setName('document');
        $this->setAttrib('enctype', 'multipart/form-data');
         
        // creating object for Zend_Form_Element_File
        $doc_file = new Zend_Form_Element_File('xml');
        $doc_file->setLabel('XML')->setRequired()
        ->setRequired(true);


        $iativalidator = new Iati_Iatischema_Validator();
        $schemaVersions = new Zend_Form_Element_Select('schemaVersion');
        $schemaVersions->setLabel("Select Schema");
        foreach ($iativalidator->getAvailableSchemaVersions() as $version) {
            $schemaVersions->addMultiOption($version, $version);
        }/*
        $response_type = new Zend_Form_Element_Checkbox('response_type');
        $response_type->setLabel('XML Format') ;*/


        /* $doc_file1 = new Zend_Form_Element_File('xsd');
         $doc_file1->setLabel('XML Schema')
         ->setRequired(true);
         */
        // creating object for submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Validate')
        ->setAttrib('id', 'submitbutton');

        // adding elements to form Object
        $this->addElements(array($doc_file,  $schemaVersions,$submit));


    }
}