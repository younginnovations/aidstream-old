<?php

class Iati_Aidstream_Element_Activity_Transaction_DisbursementChannel extends Iati_Core_BaseElement
{
    protected $className = 'DisbursementChannel';
    protected $displayName = 'Disbursement Channel';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_transaction/disbursement_channel';
}