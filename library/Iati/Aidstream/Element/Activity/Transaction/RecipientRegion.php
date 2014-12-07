<?php

class Iati_Aidstream_Element_Activity_Transaction_RecipientRegion extends Iati_Core_BaseElement
{
    protected $className = 'RecipientRegion';
    protected $displayName = 'Recipient Region';
    protected $attribs = array('id' , '@code', '@vocabulary');
    protected $iatiAttribs = array('@code', '@vocabulary');
    protected $tableName = 'iati_transaction/recipient_region';
}