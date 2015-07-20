<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_PeriodEnd extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'PeriodEnd';
    protected $displayName = 'PeriodEnd';
    protected $tableName = 'iati_result/indicator/period/period-end';
    protected $attribs = array('id' , '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $viewScriptEnabled = true;

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
                        $d['period_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }
}