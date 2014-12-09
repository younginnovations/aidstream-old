<?php

class Iati_Aidstream_Element_Activity_Transaction_AidType extends Iati_Core_BaseElement
{
    protected $className = 'AidType';
    protected $displayName = 'Aid Type';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_transaction/aid_type';
}