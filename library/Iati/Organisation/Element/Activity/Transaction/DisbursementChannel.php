<?php

class Iati_Organisation_Element_Activity_Transaction_DisbursementChannel extends Iati_Organisation_BaseElement
{
    protected $className = 'DisbursementChannel';
    protected $displayName = 'Disbursement Channel';
    protected $attribs = array('id' , '@code' , 'text');
    protected $iatiAttribs = array('@code' , 'text');
    protected $tableName = 'iati_transaction/disbursement_channel';
}