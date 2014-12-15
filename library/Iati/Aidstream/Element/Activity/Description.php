<?php

class Iati_Aidstream_Element_Activity_Description extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $tableName = 'iati_description';
    protected $attribs = array('id', '@type');
    protected $iatiAttribs = array('@type');
    protected $childElements = array('Narrative');
}