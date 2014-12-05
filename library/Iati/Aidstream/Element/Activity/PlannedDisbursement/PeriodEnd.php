<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement_PeriodEnd extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'PeriodEnd';
    protected $displayName = 'Period End';
    protected $tableName = 'iati_planned_disbursement/period_end';
    protected $attribs = array('id' , '@iso_date');
    protected $iatiAttribs = array('@iso_date');
   
}