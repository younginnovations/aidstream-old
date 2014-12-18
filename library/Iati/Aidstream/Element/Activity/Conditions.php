<?php

class Iati_Aidstream_Element_Activity_Conditions extends Iati_Core_BaseElement
{   
    protected $className = 'Conditions';
    protected $displayName = 'Conditions';
    protected $tableName = 'iati_conditions';
    protected $attribs = array('id','@attached');
    protected $iatiAttribs = array('@attached');
    protected $childElements = array('Condition');
}