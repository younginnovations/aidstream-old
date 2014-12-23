<?php

class Iati_Aidstream_Element_Activity_ActivityStatus extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'ActivityStatus';
    protected $displayName = 'Activity Status';
    protected $tableName = 'iati_activity_status';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
}