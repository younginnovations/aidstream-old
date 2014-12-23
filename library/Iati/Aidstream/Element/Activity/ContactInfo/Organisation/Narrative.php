<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Organisation_Narrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_contact_info/organisation/narrative';
    protected $attribs = array('id', '@xml_lang', 'text');
    protected $iatiAttribs = array('@xml_lang', 'text');
}