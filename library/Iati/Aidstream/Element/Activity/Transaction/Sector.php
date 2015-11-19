<?php

class Iati_Aidstream_Element_Activity_Transaction_Sector extends Iati_Core_BaseElement
{
    protected $isMultiple = false;
    protected $className = 'Sector';
    protected $displayName = 'Sector';
    protected $tableName = 'iati_transaction/sector';
    protected $attribs = array('id' , '@vocabulary', '@code');
    protected $iatiAttribs = array('@vocabulary', '@code');
    protected $childElements = array('Narrative');
}