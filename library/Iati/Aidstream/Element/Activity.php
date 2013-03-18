<?php

class Iati_Aidstream_Element_Activity extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Activity';
    protected $displayName = 'Activity';
    protected $tableName = 'iati_activity';
    protected $childElements = array('Transaction','Result');
    protected $attribs = array('id');
    
}