<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Organisation extends Iati_Core_BaseElement
{   
    protected $className = 'Organisation';
    protected $displayName = 'Organisation';
    protected $tableName = 'iati_contact_info/organisation';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');    
}