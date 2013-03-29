<?php

class Iati_Aidstream_Element_Activity_ActivityWebsite extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'ActivityWebsite';
    protected $displayName = 'Activity Website';
    protected $tableName = 'iati_activity_website';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');
}