<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier_OwnerOrg extends Iati_Core_BaseElement
{     
    protected $isMultiple = false;
    protected $className = 'OwnerOrg';
    protected $displayName = 'Owner Org';
    protected $tableName = 'iati_other_identifier/ownerorg';
    protected $attribs = array('id','@ref','@xml_lang','text');
    protected $iatiAttribs = array('@ref','@xml_lang','text');
}