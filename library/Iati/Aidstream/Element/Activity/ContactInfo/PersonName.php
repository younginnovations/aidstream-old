<?php

class Iati_Aidstream_Element_Activity_ContactInfo_PersonName extends Iati_Core_BaseElement
{
    protected $className = 'PersonName';
    protected $displayName = 'Person Name';
    protected $tableName = 'iati_contact_info/person_name';
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