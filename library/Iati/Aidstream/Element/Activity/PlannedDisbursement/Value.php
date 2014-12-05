<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement_Value extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $tableName = 'iati_planned_disbursement/value';
    protected $attribs = array('id' , '@currency', '@value_date','text');
    protected $iatiAttribs = array('@currency', '@value_date','text');
    
}