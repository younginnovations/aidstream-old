<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Language extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Language';
    protected $displayName = 'Language';
    protected $attribs = array('id' , 'text');
    protected $iatiAttribs = array('text');
    protected $tableName = 'iati_organisation/document_link/language';
    
    public function getXml($eleId , $isParentId = false , $parent = null)
    { 
        $eleName = $this->getXmlName();
        if ($isParentId)
        {
            $parentColumn = $this->getParentCoulmn();
        }
        if ($isParentId)
        {
            $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("{$parentColumn} = ?" , $eleId));
        } else
        {
            $select = $this->db->select()->where($this->db->getAdapter()->quoteInto("id = ?" , $eleId));
        }
        $row = $this->db->fetchRow($select);
        if ($row)
        {
            $data = $row->toArray();
        }
        if (!is_object($parent))
        {
            $xmlObj = new SimpleXMLElement("<$eleName>" . $data['text'] . "</$eleName>");
        } else
        {
            if ($data['text'])
            {
                $data['text'] = Iati_Core_Codelist::getCodeByAttrib($this->className , '@xml_lang' , $data['text']);
            }
            $xmlObj = $parent->addChild($eleName , $data['text']);
        }
        $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $data);

        return $xmlObj;

    }

}