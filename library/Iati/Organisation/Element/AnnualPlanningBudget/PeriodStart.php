<?php

class Iati_Organisation_Element_AnnualPlanningBudget_PeriodStart extends Iati_Organisation_BaseElement
{
    protected $isRequired = true;
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $attribs = array('id' , 'date' , 'text');
    protected $iatiAttribs = array('date' , 'text');
    protected $childElements = array('Test');
    protected $tableName = 'organisation/annual_planning_budget/period_start';
}