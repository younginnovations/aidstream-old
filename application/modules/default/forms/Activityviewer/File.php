<?php
class Form_Activityviewer_File extends Zend_Form
{
    public function init()
    {
        
        $xmlFile = new Zend_Form_Element_File('xmlFile');
        $xmlFile->setLabel('XML File');
        $xmlFile->addValidator('Extension',false, 'xml');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('View');
        
        //add element to for object
        $this->addElements(array( $xmlFile,$submit));
    }
}
