<?php

class Iati_Aidstream_Element_Activity_Conditions_Condition extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Condition';
    protected $displayName = 'Condition';
    protected $tableName = 'iati_conditions/condition';
    protected $attribs = array('id','text','@type');
    protected $iatiAttribs = array('text','@type');

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
                    if($duplicate == true)
                    {
                        $d['id'] = '';
                        $d['conditions_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }
}