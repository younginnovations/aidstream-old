<?php

class Iati_Aidstream_Element_Activity_Title extends Iati_Core_BaseElement
{
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_title';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');

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

            if ($this->hasData($data))
            {
                $elementsData = $this->getElementsData($data);
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
                    $childElement->save($data[$childElement->getClassName()] , $eleId, true);
                }
            }
            return $eleId;
        }
    }
}