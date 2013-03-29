<?php

class Iati_Aidstream_Element_Activity_IatiIdentifier extends Iati_Core_BaseElement
{
    protected $className = 'IatiIdentifier';
    protected $displayName = 'Iati Identifier';
    protected $isRequired = true;
    protected $tableName = 'iati_identifier';
    protected $attribs = array('id','text','activity_identifier');
    protected $iatiAttribs = array('text','activity_identifier');
}