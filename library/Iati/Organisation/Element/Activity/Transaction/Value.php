<?php

class Iati_Organisation_Element_Activity_Transaction_Value extends Iati_Organisation_BaseElement
{
    protected $className = 'TransactionValue';
    protected $displayName = 'Value';
    protected $isRequired = true;
    protected $attribs = array('id' , '@currency', '@value_date' , 'text');
    protected $iatiAttribs = array('@currency', '@value_date' , 'text');
    protected $tableName = 'iati_transaction/value';
}