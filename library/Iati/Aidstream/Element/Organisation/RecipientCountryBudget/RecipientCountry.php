<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget_RecipientCountry extends Iati_Core_BaseElement
{
    protected $className = 'RecipientCountry';
    protected $displayName = 'Recipient Country';
    protected $isRequired = true;
    protected $attribs = array('id' , '@code', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@code', '@xml_lang' , 'text');
    protected $tableName = 'iati_organisation/recipient_country_budget/recipient_country';
}