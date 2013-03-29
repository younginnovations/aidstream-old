<?php

class Iati_Aidstream_Element_Activity_ContactInfo_PersonName extends Iati_Core_BaseElement
{   
    protected $className = 'PersonName';
    protected $displayName = 'Person Name';
    protected $tableName = 'iati_contact_info/person_name';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');    
}