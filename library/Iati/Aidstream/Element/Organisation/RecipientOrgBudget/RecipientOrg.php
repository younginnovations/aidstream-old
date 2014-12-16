<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget_RecipientOrg extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'RecipientOrg';
    protected $displayName = 'Recipient Organisation';
    protected $tableName = 'iati_organisation/recipient_org_budget/recipient_org';
    protected $attribs = array('id' , '@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array('Narrative');
    
}