<?php

class Iati_Aidstream_Element_Activity_Budget_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $isRequired = true;
    protected $attribs = array('id', '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $tableName = 'iati_budget/period_start';

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