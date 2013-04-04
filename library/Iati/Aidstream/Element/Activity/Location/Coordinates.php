<?php

class Iati_Aidstream_Element_Activity_Location_Coordinates extends Iati_Core_BaseElement
{   
    protected $className = 'Coordinates';
    protected $displayName = 'Coordinates';
    protected $tableName = 'iati_location/coordinates';
    protected $attribs = array('id' , '@latitude', '@longitude','@precision');
    protected $iatiAttribs = array('@latitude', '@longitude','@precision');    
}