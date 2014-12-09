<?php

class Iati_Aidstream_Element_Activity_Transaction_TransactionType extends Iati_Core_BaseElement
{
    protected $className = 'TransactionType';
    protected $displayName = 'Transaction Type';
    protected $isRequired = true;
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_transaction/transaction_type';
}