<?php

class Iati_Aidstream_Element_Activity_Transaction_TiedStatus extends Iati_Core_BaseElement
{
    protected $className = 'TiedStatus';
    protected $displayName = 'Tied Status';
    protected $attribs = array('id' , '@code', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@code', '@xml_lang' , 'text');
    protected $tableName = 'iati_transaction/tied_status';
}