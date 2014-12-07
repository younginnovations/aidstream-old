<?php

class Iati_Aidstream_Element_Activity_Transaction_Sector extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Sector';
    protected $displayName = 'Sector';
    protected $attribs = array('id' , '@vocabulary', '@code');
    protected $iatiAttribs = array('@vocabulary', '@code');
    protected $tableName = 'iati_transaction/sector';
}