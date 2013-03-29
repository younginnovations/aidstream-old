<?php

class Iati_Aidstream_Element_Activity_DefaultFlowType extends Iati_Core_BaseElement
{
    protected $className = 'DefaultFlowType';
    protected $displayName = 'Default Flow Type';
    protected $tableName = 'iati_default_flow_type';
    protected $attribs = array('id','text','@xml_lang','@code');
    protected $iatiAttribs = array('text','@xml_lang','@code');
}