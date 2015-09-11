<?php

class Iati_Aidstream_Element_Activity_Conditions extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Conditions';
    protected $displayName = 'Conditions';
    protected $tableName = 'iati_conditions';
    protected $attribs = array('id','@attached');
    protected $iatiAttribs = array('@attached');
    protected $childElements = array('Condition');

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