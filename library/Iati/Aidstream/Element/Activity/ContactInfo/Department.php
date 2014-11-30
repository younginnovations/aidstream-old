<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Department extends Iati_Core_BaseElement
{   
    protected $className = 'Department';
    protected $displayName = 'Department';
    protected $tableName = 'iati_contact_info/department';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');
}