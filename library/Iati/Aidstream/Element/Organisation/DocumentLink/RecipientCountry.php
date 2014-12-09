<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_RecipientCountry extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'RecipientCountry';
    protected $displayName = 'Recipient Country';
    protected $tableName = 'iati_organisation/document_link/recipient_country';
    protected $attribs = array('id' , '@code', 'text');
    protected $iatiAttribs = array('@code', 'text');
   
}