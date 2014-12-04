<?php

class Iati_Aidstream_Element_Activity_Location extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Location';
    protected $displayName = 'Location';
    protected $tableName = 'iati_location';
    protected $attribs = array('id','@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array('LocationReach', 'LocationId', 'Name', 'Description', 'ActivityDescription', 'Administrative', 'Point', 'Exactness', 'LocationClass', 'FeatureDesignation');
}