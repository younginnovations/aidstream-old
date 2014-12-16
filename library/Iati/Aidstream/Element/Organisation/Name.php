<?php

class Iati_Aidstream_Element_Organisation_Name extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Name';
    protected $displayName = 'Name';
    protected $tableName = 'iati_organisation/name';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    
}