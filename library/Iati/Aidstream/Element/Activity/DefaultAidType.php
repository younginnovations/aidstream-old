<?php

class Iati_Aidstream_Element_Activity_DefaultAidType extends Iati_Core_BaseElement
{
    protected $className = 'DefaultAidType';
    protected $displayName = 'Default Aid Type';
    protected $tableName = 'iati_default_aid_type';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
}