<?php
class Iati_Aidstream_Element_Activity_ReportingOrg_Narrative extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_reporting_org/narrative';
    protected $attribs = array('id', '@xml_lang', 'text');
    protected $iatiAttribs = array('@xml_lang', 'text');
}