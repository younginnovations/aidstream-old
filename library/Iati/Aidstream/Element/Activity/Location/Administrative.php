<?php

class Iati_Aidstream_Element_Activity_Location_Administrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Administrative';
    protected $displayName = 'Administrative';
    protected $tableName = 'iati_location/administrative';
    protected $attribs = array('id', '@vocabulary', '@level', '@code');
    protected $iatiAttribs = array('@vocabulary', '@level', '@code');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            foreach($data as $d)
            {
                if($this->hasData($d))
                {
                    if($duplicate == true)
                    {
                        $d['id'] = '';
                        $d['location_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }
}