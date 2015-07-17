<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Category extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Category';
    protected $displayName = 'Category';
    protected $isRequired = true;
    protected $tableName = 'iati_document_link/category';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');

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

                    $d['id'] = '';
                    $d['document_link_id'] = $parentId;
                    $eleId = $this->db->insert($d);

                }
            }
            return $eleId;
        }
    }
}