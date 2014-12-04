<?php

class Iati_Aidstream_Element_Activity_Location_Point extends Iati_Core_BaseElement
{   
    protected $className = 'Point';
    protected $displayName = 'Point';
    protected $tableName = 'iati_location/point';
    protected $attribs = array('id', '@srsName');
    protected $iatiAttribs = array('@srsName');
    protected $childElements = array('Pos');   
}