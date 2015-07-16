<?php

class Iati_Aidstream_Element_Activity_ContactInfo_JobTitle extends Iati_Core_BaseElement
{
    protected $className = 'JobTitle';
    protected $displayName = 'Job Title';
    protected $tableName = 'iati_contact_info/job_title';
    protected $attribs = array('id','text' , '@xml_lang');
    protected $iatiAttribs = array('text' , '@xml_lang');

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