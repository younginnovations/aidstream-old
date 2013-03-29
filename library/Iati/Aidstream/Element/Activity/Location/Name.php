<?php

class Iati_Aidstream_Element_Activity_Location_Name extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Name';
    protected $displayName = 'Name';
    protected $tableName = 'iati_location/name';
    protected $attribs = array('id' , '@xml_lang','text');
    protected $iatiAttribs = array('@xml_lang','text');    
}