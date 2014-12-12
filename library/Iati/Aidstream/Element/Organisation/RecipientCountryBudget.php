<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'RecipientCountryBudget';
    protected $displayName = 'Recipient Country Budget';
    protected $tableName = 'iati_organisation/recipient_country_budget';
    protected $attribs = array('id');
    protected $childElements = array('RecipientCountry', 'PeriodStart', 'PeriodEnd', 'Value', 'BudgetLine');
}