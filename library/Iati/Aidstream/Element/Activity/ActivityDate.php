<?php

class Iati_Aidstream_Element_Activity_ActivityDate extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'ActivityDate';
    protected $displayName = 'Activity Date';
    protected $tableName = 'iati_activity_date';
    protected $attribs = array('id','@iso_date','@type','@xml_lang','text');
    protected $iatiAttribs = array('@iso_date','@type','@xml_lang','text');
}