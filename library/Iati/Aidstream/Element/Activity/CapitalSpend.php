<?php

class Iati_Aidstream_Element_Activity_CapitalSpend extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'CapitalSpend';
    protected $displayName = 'Capital Spend';
    protected $tableName = 'iati_capital_spend';
    protected $attribs = array('id','@percentage');
    protected $iatiAttribs = array('@percentage');
}