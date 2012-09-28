<?php

class Iati_Organisation_Element_AnnualPlanningBudget_PeriodStart_Test extends Iati_Organisation_BaseElement
{
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Test';
    protected $displayName = 'Test';
    protected $attribs = array('id' , 'date' , 'text');
    protected $iatiAttribs = array('date' , 'text');
    //protected $childElements = array('TestChild');
    protected $tableName = 'organisation/annual_planning_budget/period_start/test';
}