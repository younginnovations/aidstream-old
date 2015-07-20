<?php

class Iati_Aidstream_Element_Activity_Budget_PeriodEnd extends Iati_Core_BaseElement
{
    protected $className = 'PeriodEnd';
    protected $displayName = 'Period End';
    protected $isRequired = true;
    protected $attribs = array('id' , '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $tableName = 'iati_budget/period_end';

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