<?php

class Iati_Aidstream_Element_Activity_Conditions_Condition extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;    
    protected $className = 'Condition';
    protected $displayName = 'Condition';
    protected $tableName = 'iati_conditions/condition';
    protected $attribs = array('id', '@type');
    protected $iatiAttribs = array('@type');
    protected $childElements = array('Narrative');
}