<?php

class Iati_Aidstream_Element_Activity_Location_Exactness extends Iati_Core_BaseElement
{
    protected $className = 'Exactness';
    protected $displayName = 'Exactness';
    protected $tableName = 'iati_location/exactness';
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