<?php
class Form_Activityviewer_Url extends Zend_Form
{
    public function init()
    {
        $xmlUrl = new Zend_Form_Element_Text('xmlUrl');
        $xmlUrl->setLabel('XML URL')->setAttrib("class", "xmlUrl");
       
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('View');
        
        //add element to for object
        $this->addElements(array($xmlUrl,$submit));
    }
}
