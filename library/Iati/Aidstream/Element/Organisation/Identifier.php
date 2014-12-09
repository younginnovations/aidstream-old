<?php

class Iati_Aidstream_Element_Organisation_Identifier extends Iati_Core_BaseElement
{
    protected $className = 'Identifier';
    protected $displayName = ' Organisation Identifier';
    protected $xmlName = 'organisation-identifier';
    protected $isRequired = true;
    protected $tableName = 'iati_organisation/identifier';
    protected $attribs = array('id' ,'text');
    protected $iatiAttribs = array('text');
    
    public function getForm($ajax = false)
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        $eleForm = new $formname(array('element' => $this));
        $eleForm->wrapForm($this->getDisplayName() , $this->getIsRequired());
        return $eleForm->getFormDefination();        
    }
}