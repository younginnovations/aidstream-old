<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier extends Iati_Core_BaseElement
{     
    protected $isMultiple = true;
    protected $className = 'OtherActivityIdentifier';
    protected $displayName = 'Other Identifier';
    protected $xmlName = 'otherIdentifier';
    protected $tableName = 'iati_other_identifier';
    protected $attribs = array('id','@ref','@type');
    protected $iatiAttribs = array('@ref','@type');
    protected $childElements = array('OwnerOrg');

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