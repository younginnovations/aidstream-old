<?php

class Iati_Aidstream_Element_Activity_Location_LocationType extends Iati_Core_BaseElement
{
    protected $className = 'LocationType';
    protected $displayName = 'Location Type';
    protected $tableName = 'iati_location/location_type';
    protected $attribs = array('id' , '@xml_lang','text','@code');
    protected $iatiAttribs = array('@xml_lang','text','@code');

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