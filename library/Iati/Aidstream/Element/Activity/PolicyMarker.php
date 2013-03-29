<?php

class Iati_Aidstream_Element_Activity_PolicyMarker extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'PolicyMarker';
    protected $displayName = 'Policy Marker';
    protected $tableName = 'iati_policy_marker';
    protected $attribs = array('id','text','@xml_lang','@code','@significance','@vocabulary');
    protected $iatiAttribs = array('text','@xml_lang','@code','@significance','@vocabulary');
}