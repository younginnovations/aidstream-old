<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Title extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $isRequired = true;
    protected $attribs = array('id' , '@xml_lang' , 'text');
    protected $iatiAttribs = array('@xml_lang' , 'text');
    protected $tableName = 'iati_document_link/title';

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
                        $d['document_link_id'] = $parentId;
                        $eleId = $this->db->insert($d);
                    }
                }
            }
            return $eleId;
        }
    }
}