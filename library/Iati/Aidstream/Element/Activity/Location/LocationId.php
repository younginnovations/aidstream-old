    <?php

class Iati_Aidstream_Element_Activity_Location_LocationId extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'LocationId';
    protected $displayName = 'Location Id';
    protected $tableName = 'iati_location/location_id';
    protected $attribs = array('id', '@vocabulary', '@code');
    protected $iatiAttribs = array('@vocabulary', '@code'); 

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
                        $d['location_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }   
}