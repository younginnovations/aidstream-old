    <?php

class Iati_Aidstream_Element_Activity_Location_LocationId extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'LocationId';
    protected $displayName = 'Location Id';
    protected $tableName = 'iati_location/location_id';
    protected $attribs = array('id', '@vocabulary', '@code');
    protected $iatiAttribs = array('@vocabulary', '@code');    
}