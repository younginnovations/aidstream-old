<?php

class Iati_Aidstream_Element_Activity_RecipientCountry extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'RecipientCountry';
    protected $displayName = 'Recipient Country';
    protected $tableName = 'iati_recipient_country';
    protected $attribs = array('id','@code','@percentage');
    protected $iatiAttribs = array('@code','@percentage');
    protected $childElements = array('Narrative');
}