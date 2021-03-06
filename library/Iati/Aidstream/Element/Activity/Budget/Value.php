<?php

class Iati_Aidstream_Element_Activity_Budget_Value extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $attribs = array('id' , '@currency', '@value_date' , 'text');
    protected $iatiAttribs = array('@currency', '@value_date' , 'text');
    protected $tableName = 'iati_budget/value';

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
                $data['id'] = '';
                $data['budget_id']= $parentId;
                $eleId = $this->db->insert($data);
            }
            return $eleId;
        }
    }
}