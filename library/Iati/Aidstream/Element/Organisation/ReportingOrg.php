<?php

class Iati_Aidstream_Element_Organisation_ReportingOrg extends Iati_Core_BaseElement
{
    protected $className = 'ReportingOrg';
    protected $displayName = 'Reporting Organisation';
    protected $isRequired = true;
    protected $tableName = 'iati_organisation/reporting_org';
    protected $attribs = array('id','@ref','@type','@xml_lang','text');
    protected $iatiAttribs = array('@ref','@type','@xml_lang','text');
    
    public function getForm($ajax = false)
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        $eleForm = new $formname(array('element' => $this));
        $eleForm->wrapForm($this->getDisplayName() , $this->getIsRequired());
        return $eleForm->getFormDefination();        
    }
}