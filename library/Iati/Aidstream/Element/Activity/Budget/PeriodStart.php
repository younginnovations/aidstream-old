<?php

class Iati_Aidstream_Element_Organisation_TotalBudget_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $isRequired = true;
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_organisation/total_budget/period_start';
}