<?php

class Iati_Aidstream_Element_Activity_LegacyData extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'LegacyData';
    protected $displayName = "Legacy Data";
    protected $tableName = 'iati_legacy_data';
    protected $attribs = array('id', '@name' , '@value' , '@iati_equivalent');
    protected $iatiAttribs = array('@name' , '@value' , '@iati_equivalent');
}