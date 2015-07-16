<?php

class Iati_Aidstream_Element_Activity_Location_GazetteerEntry extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'GazetteerEntry';
    protected $displayName = 'Gazetteer Entry';
    protected $tableName = 'iati_location/gazetteer_entry';
    protected $attribs = array('id' , 'text', '@gazetteer_ref');
    protected $iatiAttribs = array('text', '@gazetteer_ref');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            foreach ($data as $d)
            {
                if ($this->hasData($d))
                {
                    $d['id'] = '';
                    $d['location_id'] = $parentId;
                    $eleId = $this->db->insert($d);
                }
            }
            return $eleId;
        }
    }
}