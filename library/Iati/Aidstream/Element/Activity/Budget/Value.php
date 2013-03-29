<?php

class Iati_Aidstream_Element_Activity_Budget_Value extends Iati_Core_BaseElement
{
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $isRequired = true;
    protected $attribs = array('id' , '@currency', '@value_date' , 'text');
    protected $iatiAttribs = array('@currency', '@value_date' , 'text');
    protected $tableName = 'iati_budget/value';
}