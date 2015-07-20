<?php

class Iati_Aidstream_Element_Activity_Location_LocationReach extends Iati_Core_BaseElement
{   
    protected $className = 'LocationReach';
    protected $displayName = 'Location Reach';
    protected $tableName = 'iati_location/location_reach';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            if($this->hasData($data))
            {
                $data['id'] = '';
                $data['location_id'] = $parentId;
                $eleId = $this->db->insert($data);
            }
            return $eleId;
        }
    }    
}