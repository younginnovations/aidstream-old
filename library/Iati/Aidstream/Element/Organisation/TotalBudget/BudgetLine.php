<?php

class Iati_Aidstream_Element_Organisation_TotalBudget_BudgetLine extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'BudgetLine';
    protected $displayName = 'Budget Line';
    protected $tableName = 'iati_organisation/total_budget/budget_line';
    protected $attribs = array('id' , '@ref', 'text');
    protected $iatiAttribs = array('@ref' , 'text');
    protected $childElements = array('Value','Narrative');
}