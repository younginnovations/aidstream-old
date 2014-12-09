<?php

class Iati_Aidstream_Element_Activity_DefaultTiedStatus extends Iati_Core_BaseElement
{
    protected $className = 'DefaultTiedStatus';
    protected $displayName = 'Default Tied Status';
    protected $tableName = 'iati_default_tied_status';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
}