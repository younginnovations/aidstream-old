<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget_Value extends Iati_Core_BaseElement
{
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $isRequired = true;
    protected $attribs = array('id', '@currency', '@value_date', 'text');
    protected $iatiAttribs = array('@currency', '@value_date', 'text');
    protected $tableName = 'iati_organisation/recipient_org_budget/value';
}