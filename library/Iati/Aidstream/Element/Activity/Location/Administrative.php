<?php

class Iati_Aidstream_Element_Activity_Location_Administrative extends Iati_Core_BaseElement
{
    protected $className = 'Administrative';
    protected $displayName = 'Administrative';
    protected $tableName = 'iati_location/administrative';
    protected $attribs = array('id' , '@country', '@adm1','@adm2','text');
    protected $iatiAttribs = array('@country', '@adm1','@adm2','text');
    protected $viewScriptEnabled = true;
    protected $viewScript = 'administrative.phtml';

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
                    $data['location_id'] = $parentId;
                    $eleId = $this->db->insert($data);
                }
            }
            return $eleId;
        }
    }
}