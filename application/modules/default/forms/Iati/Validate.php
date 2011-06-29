<?php
class Form_Iati_Validate extends Zend_Form
{
    public function init()
    {

        $this->setName('document');
         
        $this->setAttrib('enctype', 'multipart/form-data');
         
        // creating object for Zend_Form_Element_File
        $doc_file = new Zend_Form_Element_File('xml');
        $doc_file->setLabel('XML')->setRequired()
        ->setRequired(true);

        $doc_file1 = new Zend_Form_Element_File('xsd');
        $doc_file1->setLabel('XML Schema')->setRequired()
        ->setRequired(true);

       /* $response_type = new Zend_Form_Element_Checkbox('response_type');
        $response_type->setLabel('XML Format') ;*/

        // creating object for submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Validate')
        ->setAttrib('id', 'submitbutton');
        
        $reset = new Zend_Form_Element_Reset('reset');
        $reset->setValue('Reset');

        // adding elements to form Object
        $this->addElements(array($doc_file, $doc_file1, $submit, $reset));


    }
}