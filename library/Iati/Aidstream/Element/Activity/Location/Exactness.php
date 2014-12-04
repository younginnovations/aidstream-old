<?php

class Iati_Aidstream_Element_Activity_Location_Exactness extends Iati_Core_BaseElement
{   
    protected $className = 'Exactness';
    protected $displayName = 'Exactness';
    protected $tableName = 'iati_location/exactness';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');   
}