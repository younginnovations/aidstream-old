<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Organisation extends Iati_Core_BaseElement
{
    protected $className = 'Organisation';
    protected $displayName = 'Organisation';
    protected $tableName = 'iati_contact_info/organisation';
    protected $attribs = array('id','text');
    protected $iatiAttribs = array('text');

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
                    $data['contact_info_id'] = $parentId;
                    $eleId = $this->db->insert($data);
                }
            }
            return $eleId;
        }
    }
}