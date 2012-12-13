<?php

class Iati_Aidstream_Element_Organisation_RecipientOrgBudget_RecipientOrg extends Iati_Core_BaseElement
{
    protected $className = 'RecipientOrg';
    protected $displayName = 'Recipient Organsation';
    protected $attribs = array('id' , '@ref', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@ref', '@xml_lang' , 'text');
    protected $tableName = 'iati_organisation/recipient_org_budget/recipient_org';
}