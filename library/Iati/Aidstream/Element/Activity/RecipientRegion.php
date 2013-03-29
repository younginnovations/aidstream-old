<?php

class Iati_Aidstream_Element_Activity_RecipientRegion extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'RecipientRegion';
    protected $displayName = 'Recipient Region';
    protected $tableName = 'iati_recipient_region';
    protected $attribs = array('id','text','@xml_lang','@code','@percentage');
    protected $iatiAttribs = array('text','@xml_lang','@code','@percentage');
}