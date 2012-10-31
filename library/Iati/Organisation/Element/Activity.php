<?php

class Iati_Organisation_Element_Activity extends Iati_Organisation_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Activity';
    protected $displayName = 'Activity';
    protected $tableName = 'iati_activity';
    protected $childElements = array('Transaction');
    protected $attribs = array('id');
    
}