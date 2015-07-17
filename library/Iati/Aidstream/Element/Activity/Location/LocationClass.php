<?php

class Iati_Aidstream_Element_Activity_Location_LocationClass extends Iati_Core_BaseElement
{
    protected $className = 'LocationClass';
    protected $displayName = 'Location Class';
    protected $tableName = 'iati_location/location_class';
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