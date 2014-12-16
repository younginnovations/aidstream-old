<?php

class Iati_Aidstream_Element_Activity_ParticipatingOrg extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'ParticipatingOrg';
    protected $displayName = 'Participating Organisation';
    protected $tableName = 'iati_participating_org';
    protected $attribs = array('id','@ref','@role','@type');
    protected $iatiAttribs = array('@ref','@role','@type');
    protected $childElements = array('Narrative');
}