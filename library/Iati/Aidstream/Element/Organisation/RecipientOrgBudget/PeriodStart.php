<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PreiodStart';
    protected $displayName = 'Preiod Start';
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_organisation/recipient_org_budget/period_start';
}