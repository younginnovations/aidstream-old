<?php

class Iati_Aidstream_Element_Activity_Location_Administrative extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Administrative';
    protected $displayName = 'Administrative';
    protected $tableName = 'iati_location/administrative';
    protected $attribs = array('id', '@vocabulary', '@level', '@code');
    protected $iatiAttribs = array('@vocabulary', '@level', '@code');
}