<?php

class Iati_Organisation_Element_Activity_Transaction_TiedStatus extends Iati_Organisation_BaseElement
{
    protected $className = 'TiedStatus';
    protected $displayName = 'Tied Status';
    protected $attribs = array('id' , '@code', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@code', '@xml_lang' , 'text');
    protected $tableName = 'iati_transaction/tied_status';
}