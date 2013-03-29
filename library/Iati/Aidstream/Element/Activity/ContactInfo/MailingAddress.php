<?php

class Iati_Aidstream_Element_Activity_ContactInfo_MailingAddress extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'MailingAddress';
    protected $displayName = 'Mailing Address';
    protected $tableName = 'iati_contact_info/mailing_address';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');    
}