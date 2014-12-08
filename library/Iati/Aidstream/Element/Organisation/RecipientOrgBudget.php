<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'RecipientOrgBudget';
    protected $displayName = 'Recipient Organisation Budget';
    protected $tableName = 'iati_organisation/recipient_org_budget';
    protected $attribs = array('id');
    protected $childElements = array('RecipientOrg' , 'Value' , 'PeriodStart' , 'PeriodEnd','BudgetLine');
}