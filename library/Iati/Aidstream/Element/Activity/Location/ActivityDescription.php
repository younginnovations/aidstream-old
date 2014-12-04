<?php

class Iati_Aidstream_Element_Activity_Location_ActivityDescription extends Iati_Core_BaseElement
{   
    protected $className = 'ActivityDescription';
    protected $displayName = 'Activity Description';
    protected $tableName = 'iati_location/activity_description';
    protected $attribs = array('id', 'text');
    protected $iatiAttribs = array('text');
}