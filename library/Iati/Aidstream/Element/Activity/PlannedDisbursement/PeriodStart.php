<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement_PeriodStart extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $tableName = 'iati_planned_disbursement/period_start';
    protected $attribs = array('id' , '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    
}