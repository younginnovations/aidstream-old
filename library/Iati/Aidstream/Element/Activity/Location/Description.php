<?php

class Iati_Aidstream_Element_Activity_Location_Description extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $tableName = 'iati_location/description';
    protected $attribs = array('id' , '@xml_lang','text');
    protected $iatiAttribs = array('@xml_lang','text');    
}