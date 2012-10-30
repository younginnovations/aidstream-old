<?php

class Iati_Organisation_Element_Activity_Transaction_TransactionType extends Iati_Organisation_BaseElement
{
    protected $className = 'TransactionType';
    protected $displayName = 'Transaction Type';
    protected $isRequired = true;
    protected $attribs = array('id' , '@code', '@type' , 'text');
    protected $iatiAttribs = array('@code', '@type' , 'text');
    protected $tableName = 'iati_transaction/transaction_type';
}