<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Website extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Website';
    protected $displayName = 'Website';
    protected $tableName = 'iati_contact_info/website';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');    
}