<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget_RecipientCountry extends Iati_Core_BaseElement
{
	protected $isRequired = true;
	protected $className = 'RecipientCountry';
    protected $displayName = 'Recipient Country';
   	protected $tableName = 'iati_organisation/recipient_country_budget/recipient_country';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
    protected $childElements = array('Narrative');
    
}