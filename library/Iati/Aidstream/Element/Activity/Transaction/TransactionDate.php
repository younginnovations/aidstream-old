<?php

class Iati_Aidstream_Element_Activity_Transaction_TransactionDate extends Iati_Core_BaseElement
{
    protected $className = 'TransactionDate';
    protected $displayName = 'Transaction Date';
    protected $attribs = array('id' , '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $tableName = 'iati_transaction/transaction_date';
}