<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget_BudgetLine extends Iati_Core_BaseElement
{
	protected $isMultiple = true;
    protected $className = 'BudgetLine';
    protected $displayName = 'Budget Line';
    protected $tableName = 'iati_organisation/recipient_org_budget/budget_line';
    protected $attribs = array('id', '@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array('Value', 'Narrative');

}