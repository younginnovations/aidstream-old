<?php

class Iati_Aidstream_Element_Organisation_RecipientCountryBudget_RecipientCountry_Narrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_organisation/recipient_country_budget/recipient_country/nar';
    protected $attribs = array('id', '@xml_lang', 'text');
    protected $iatiAttribs = array('@xml_lang', 'text');
}