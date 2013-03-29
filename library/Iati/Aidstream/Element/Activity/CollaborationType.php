<?php

class Iati_Aidstream_Element_Activity_CollaborationType extends Iati_Core_BaseElement
{
    protected $className = 'CollaborationType';
    protected $displayName = 'Collaboration Type';
    protected $tableName = 'iati_collaboration_type';
    protected $attribs = array('id','text','@xml_lang','@code');
    protected $iatiAttribs = array('text','@xml_lang','@code');
}