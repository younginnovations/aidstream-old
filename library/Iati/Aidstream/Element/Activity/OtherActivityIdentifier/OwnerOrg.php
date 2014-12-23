<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier_OwnerOrg extends Iati_Core_BaseElement
{     
    protected $className = 'OwnerOrg';
    protected $displayName = 'Owner Org';
    protected $tableName = 'iati_other_identifier/ownerorg';
    protected $attribs = array('id','@ref');
    protected $iatiAttribs = array('@ref');
    protected $childElements = array('Narrative');
}