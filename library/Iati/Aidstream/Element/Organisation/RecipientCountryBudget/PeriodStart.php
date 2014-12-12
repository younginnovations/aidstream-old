<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $isRequired = true;
    protected $attribs = array('id', '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $tableName = 'iati_organisation/recipient_country_budget/period_start';
}