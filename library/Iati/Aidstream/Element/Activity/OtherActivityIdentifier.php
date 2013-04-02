<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier extends Iati_Core_BaseElement
{     
    protected $isMultiple = true;
    protected $className = 'OtherActivityIdentifier';
    protected $displayName = 'Other Activity Identifier';
    protected $xmlName = 'otherIdentifier';
    protected $tableName = 'iati_other_identifier';
    protected $attribs = array('id','@owner_ref','@owner_name','text');
    protected $iatiAttribs = array('@owner_ref','@owner_name','text');
}