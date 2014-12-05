<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier extends Iati_Core_BaseElement
{     
    protected $isMultiple = true;
    protected $className = 'OtherActivityIdentifier';
    protected $displayName = 'Other Identifier';
    protected $xmlName = 'otherIdentifier';
    protected $tableName = 'iati_other_identifier';
    protected $attribs = array('id','@ref','@type');
    protected $iatiAttribs = array('@ref','@type');
    protected $childElements = array('OwnerOrg');
}