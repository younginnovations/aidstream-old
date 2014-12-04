<?php

class Iati_Aidstream_Element_Activity_Location_Description extends Iati_Core_BaseElement
{   
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $tableName = 'iati_location/description';
    protected $attribs = array('id', 'text');
    protected $iatiAttribs = array('text');
}