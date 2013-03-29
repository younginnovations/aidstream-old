<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'PlannedDisbursement';
    protected $displayName = 'Planned Disbursement';
    protected $tableName = 'iati_planned_disbursement';
    protected $attribs = array('id','@updated');
    protected $iatiAttribs = array('@updated');
    protected $childElements = array('PeriodStart' , 'PeriodEnd','Value');
}