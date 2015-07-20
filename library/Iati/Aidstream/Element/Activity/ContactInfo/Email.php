<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Email extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Email';
    protected $displayName = 'Email';
    protected $tableName = 'iati_contact_info/email';
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
            foreach($data as $d)
            {
                if($this->hasData($d))
                {
                    if($duplicate == true)
                    {
                        $d['id'] = '';
                        $d['contact_info_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }
}