<?php

class Iati_Aidstream_Element_Activity_ParticipatingOrg_Narrative extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Narrative';
    protected $displayName = 'Narrative';
    protected $tableName = 'iati_participating_org/narrative';
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
                    $d['participating_org_id'] = $parentId;
                    $eleId = $this->db->insert($d);
                }
            }
            return $eleId;
        }
    }
}