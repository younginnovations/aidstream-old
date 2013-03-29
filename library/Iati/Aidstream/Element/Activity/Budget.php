<?php

class Iati_Aidstream_Element_Activity_Budget extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Budget';
    protected $displayName = 'Budget';
    protected $tableName = 'iati_budget';
    protected $attribs = array('id','@type');
    protected $iatiAttribs = array('@type');
    protected $childElements = array('PeriodStart' , 'PeriodEnd','Value');
}