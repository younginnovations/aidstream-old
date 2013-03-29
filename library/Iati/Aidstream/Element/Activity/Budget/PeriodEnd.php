<?php

class Iati_Aidstream_Element_Activity_Budget_PeriodEnd extends Iati_Core_BaseElement
{
    protected $className = 'PeriodEnd';
    protected $displayName = 'Period End';
    protected $isRequired = true;
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_budget/period_end';
}