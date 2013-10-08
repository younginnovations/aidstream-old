<?php

class Iati_Aidstream_Element_Activity_ActivityScope extends Iati_Core_BaseElement
{
    protected $className = 'ActivityScope';
    protected $displayName = 'Activity Scope';
    protected $tableName = 'iati_activity_scope';
    protected $attribs = array('id','text','@xml_lang','@code');
    protected $iatiAttribs = array('text','@xml_lang','@code');
}