<?php

class Iati_Aidstream_Element_Activity_ContactInfo_PersonName_Narrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_contact_info/person_name/narrative';
    protected $attribs = array('id', '@xml_lang', 'text');
    protected $iatiAttribs = array('@xml_lang', 'text');

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
                    $d['person_name_id'] = $parentId;
                    $eleId = $this->db->insert($d);
                }
            }
            return $eleId;
        }
    }
}