<?php

class Iati_Aidstream_Element_Activity_Location_FeatureDesignation extends Iati_Core_BaseElement
{   
    protected $className = 'FeatureDesignation';
    protected $displayName = 'Feature Designation';
    protected $tableName = 'iati_location/feature_designation';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
}