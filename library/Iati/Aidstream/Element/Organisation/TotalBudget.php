<?php

class Iati_Aidstream_Element_Organisation_TotalBudget extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'TotalBudget';
    protected $displayName = 'Total Budget';
    protected $tableName = 'iati_organisation/total_budget';
    protected $attribs = array('id');
    protected $childElements = array('PeriodStart', 'PeriodEnd', 'Value', 'BudgetLine');
}