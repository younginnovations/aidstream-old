<?php

class Iati_Aidstream_Element_Activity_Transaction_RecipientCountry extends Iati_Core_BaseElement
{
    protected $className = 'RecipientCountry';
    protected $displayName = 'Recipient Country';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_transaction/recipient_country';
}