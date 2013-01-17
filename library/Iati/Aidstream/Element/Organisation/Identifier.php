<?php

class Iati_Aidstream_Element_Organisation_Identifier extends Iati_Core_BaseElement
{
    protected $className = 'Identifier';
    protected $displayName = 'Identifier';
    protected $tableName = 'iati_organisation/identifier';
    protected $attribs = array('id' ,'text');
}