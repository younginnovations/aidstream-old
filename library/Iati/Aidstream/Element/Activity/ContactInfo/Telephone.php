<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Telephone extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Telephone';
    protected $displayName = 'Telephone';
    protected $tableName = 'iati_contact_info/telephone';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');    
}