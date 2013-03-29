<?php

class Iati_Aidstream_Element_Activity_Location_LocationType extends Iati_Core_BaseElement
{   
    protected $className = 'LocationType';
    protected $displayName = 'Location Type';
    protected $tableName = 'iati_location/location_type';
    protected $attribs = array('id' , '@xml_lang','text','@code');
    protected $iatiAttribs = array('@xml_lang','text','@code');    
}