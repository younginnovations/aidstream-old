<?php

class Iati_Organisation_Element_Activity_Transaction_TransactionDate extends Iati_Organisation_BaseElement
{
    protected $className = 'TransactionDate';
    protected $displayName = 'Transaction Date';
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_transaction/transaction_date';
}