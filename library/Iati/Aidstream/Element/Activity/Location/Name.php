<?php

class Iati_Aidstream_Element_Activity_Location_Name extends Iati_Core_BaseElement
{   
    protected $className = 'Name';
    protected $displayName = 'Name';
    protected $tableName = 'iati_location/name';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');    
}