<?php

class Iati_Aidstream_Element_Activity_ReportingOrg extends Iati_Core_BaseElement
{
    protected $className = 'ReportingOrg';
    protected $displayName = 'Reporting Organisation';
    protected $isRequired = true;
    protected $tableName = 'iati_reporting_org';
    protected $attribs = array('id','@ref','@type','@xml_lang','text');
    protected $iatiAttribs = array('@ref','@type','@xml_lang','text');
}