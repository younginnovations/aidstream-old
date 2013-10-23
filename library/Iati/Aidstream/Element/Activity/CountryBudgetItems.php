<?php

class Iati_Aidstream_Element_Activity_CountryBudgetItems extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'CountryBudgetItems';
    protected $tableName = 'iati_country_budget_items';
    protected $attribs = array('id','@vocabulary');
    protected $iatiAttribs = array('@vocabulary');
    protected $childElements = array('BudgetItem');
    protected $viewScriptEnabled = true;
}