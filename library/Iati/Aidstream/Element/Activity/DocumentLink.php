<?php

class Iati_Aidstream_Element_Activity_DocumentLink extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'DocumentLink';
    protected $displayName = 'Document Link';
    protected $tableName = 'iati_document_link';
    protected $attribs = array('id','@url','@format');
    protected $iatiAttribs = array('@url','@format');
    protected $childElements = array('Language' , 'Category' , 'Title');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            if ($parentId)
            {
                $parentColumnName = $this->getParentCoulmn();
            }
            if ($this->isMultiple)
            {
                foreach ($data as $elementData)
                {
                    if ($this->hasData($elementData))
                    {
                        $elementsData = $this->getElementsData($elementData);
                        if ($parentId)
                        {
                            $elementsData[$parentColumnName] = $parentId;
                        }
                        $elementsData['id'] = '';
                        $eleId = $this->db->insert($elementsData);
                    }

                    // If children are present create children elements and call their save function.
                    if (!empty($this->childElements))
                    {
                        foreach ($this->childElements as $childElementClass)
                        {
                            $childElementName = get_class($this) . "_$childElementClass";
                            $childElement = new $childElementName();
                            $childElement->save($elementData[$childElement->getClassName()] , $eleId, true);

                        }
                    }
                }
            }
            return $eleId;
        }
    }
}