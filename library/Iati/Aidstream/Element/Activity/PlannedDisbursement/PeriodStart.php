<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement_PeriodStart extends Iati_Core_BaseElement
{
    protected $className = 'PeriodStart';
    protected $displayName = 'Period Start';
    protected $isRequired = true;
    protected $attribs = array('id' , '@iso_date', 'text');
    protected $iatiAttribs = array('@iso_date' , 'text');
    protected $tableName = 'iati_planned_disbursement/period_start';

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
                    $data['planned_disbursement_id'] = $parentId;
                    $eleId = $this->db->insert($data);
                }
            }
            return $eleId;
        }
    }
}