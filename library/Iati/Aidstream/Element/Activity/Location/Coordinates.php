<?php

class Iati_Aidstream_Element_Activity_Location_Coordinates extends Iati_Core_BaseElement
{
    protected $className = 'Coordinates';
    protected $displayName = 'Coordinates';
    protected $tableName = 'iati_location/coordinates';
    protected $attribs = array('id' , '@latitude', '@longitude','@precision');
    protected $iatiAttribs = array('@latitude', '@longitude','@precision');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            if ($this->hasData($data))
            {
                if($duplicate == true)
                {
                    $data['id'] = '';
                    $data['location_id'] = $parentId;
                    $eleId = $this->db->insert($data);
                }
            }
            return $eleId;
        }
    }
}