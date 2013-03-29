<?php

class Iati_Aidstream_Element_Activity_ParticipatingOrg extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'ParticipatingOrg';
    protected $displayName = 'Participating Organization';
    protected $tableName = 'iati_participating_org';
    protected $attribs = array('id','text','@xml_lang','@type','@role','@ref');
    protected $iatiAttribs = array('text','@xml_lang','@type','@role','@ref');
}