<?php

class Iati_Organisation_Element_Activity_Transaction_FinanceType extends Iati_Organisation_BaseElement
{
    protected $className = 'FinanceType';
    protected $displayName = 'Finance Type';
    protected $attribs = array('id' , '@code' , 'text' , '@xml_lang');
    protected $iatiAttribs = array('@code' , 'text' , '@xml_lang');
    protected $tableName = 'iati_transaction/finance_type';
}