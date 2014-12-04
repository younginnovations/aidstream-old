<?php

class Iati_Aidstream_Element_Activity_Location_LocationClass extends Iati_Core_BaseElement
{   
    protected $className = 'LocationClass';
    protected $displayName = 'Location Class';
    protected $tableName = 'iati_location/location_class';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
}