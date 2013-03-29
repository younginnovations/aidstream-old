<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Email extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Email';
    protected $displayName = 'Email';
    protected $tableName = 'iati_contact_info/email';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');    
}