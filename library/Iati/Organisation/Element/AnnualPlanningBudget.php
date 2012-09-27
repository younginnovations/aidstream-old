<?php

class Iati_Organisation_Element_AnnualPlanningBudget extends Iati_Organisation_Element_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'AnnualPlanningBudget';
    protected $displayName = 'Annual Planning Budget';
    protected $tableName = 'organisation/annual_planning_budget';
    protected $childElements = array('PeriodStart' , 'PeriodEnd');
    protected $attribs = array('id');
    
}