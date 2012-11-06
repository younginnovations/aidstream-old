<?php

class Iati_Aidstream_Element_Activity_Transaction_Value extends Iati_Core_BaseElement
{
    protected $className = 'TransactionValue';
    protected $displayName = 'Value';
    protected $isRequired = true;
    protected $attribs = array('id' , '@currency', '@value_date' , 'text');
    protected $iatiAttribs = array('@currency', '@value_date' , 'text');
    protected $tableName = 'iati_transaction/value';
}