<?php

class Iati_Aidstream_Element_Activity_PolicyMarker extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'PolicyMarker';
    protected $displayName = 'Policy Marker';
    protected $tableName = 'iati_policy_marker';
    protected $attribs = array('id','@vocabulary','@code','@significance');
    protected $iatiAttribs = array('@vocabulary','@code','@significance');
    protected $childElements = array('Narrative');
}