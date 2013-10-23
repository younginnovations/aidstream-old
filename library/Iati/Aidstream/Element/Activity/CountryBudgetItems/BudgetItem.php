<?php

class Iati_Aidstream_Element_Activity_CountryBudgetItems_BudgetItem extends Iati_Core_BaseElement
{
    protected $className = 'BudgetItem';
    protected $tableName = 'iati_country_budget_items/budget_item';
    protected $isMultiple = true;
    protected $attribs = array('id','@code' , '@percentage');
    protected $iatiAttribs = array('@code' , '@percentage');
    protected $childElements = array('Description');
    protected $viewScriptEnabled = true;
}