<?php

class Iati_Aidstream_Element_Activity_Budget_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $isRequired = true;
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_budget/period_start';
}