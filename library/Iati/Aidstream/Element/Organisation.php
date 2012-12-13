<?php

class Iati_Aidstream_Element_Organisation extends Iati_Core_BaseElement
{
    protected $isMultiple = false;
    protected $className = 'Organisation';
    protected $displayName = 'Organisation';
    protected $tableName = 'iati_organisation';
    protected $childElements = array('Name' , 'TotalBudget' , 'RecipientOrgBudget' , 'RecipientCountryBudget' , 'DocumentLink');
    protected $attribs = array('id' , '@xml_lang' , '@default_currency' , '@last_updated_datetime' , 'account_id');
    protected $iatiAttribs = array('@xml_lang' , '@default_currency' , '@last_updated_datetime');
    
}