<?php

class Iati_Aidstream_Element_Activity_PlannedDisbursement_Value extends Iati_Core_BaseElement
{
    protected $className = 'Value';
    protected $displayName = 'Value';
    protected $isRequired = true;
    protected $attribs = array('id' , '@currency', '@value_date' , 'text');
    protected $iatiAttribs = array('@currency', '@value_date' , 'text');
    protected $tableName = 'iati_planned_disbursement/value';

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