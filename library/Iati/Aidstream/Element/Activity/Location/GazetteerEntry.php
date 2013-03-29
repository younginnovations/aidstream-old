<?php

class Iati_Aidstream_Element_Activity_Location_GazetteerEntry extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'GazetteerEntry';
    protected $displayName = 'Gazetteer Entry';
    protected $tableName = 'iati_location/gazetteer_entry';
    protected $attribs = array('id' , 'text', '@gazetteer_ref');
    protected $iatiAttribs = array('text', '@gazetteer_ref');    
}