<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget_BudgetLine_Value extends Iati_Core_BaseElement
{
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $tableName = 'iati_organisation/recipient_country_budget/budget_line/value';
    protected $attribs = array('id', '@currency', '@value_date', 'text');
    protected $iatiAttribs = array('@currency', '@value_date', 'text');

}