<?php

class Iati_Organisation_Element_AnnualPlanningBudget_PeriodStart_Test_TestChild extends Iati_Organisation_Element_BaseElement
{
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Test';
    protected $displayName = 'Test child';
    protected $iatiAttribs = array('date' , 'text');
    protected $tableName = 'organisation/annual_planning_budget/period_start/test/test_child';
    
    public function __construct()
    {
        self::$count++;
    }
}