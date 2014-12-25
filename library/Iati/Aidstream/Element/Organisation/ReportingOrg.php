<?php

class Iati_Aidstream_Element_Organisation_ReportingOrg extends Iati_Core_BaseElement
{
    protected $className = 'ReportingOrg';
    protected $displayName = 'Reporting Organisation';
    protected $isRequired = true;
    protected $tableName = 'iati_organisation/reporting_org';
    protected $attribs = array('id','@ref','@type');
    protected $iatiAttribs = array('@ref','@type');
    protected $childElements = array('Narrative');
    
}