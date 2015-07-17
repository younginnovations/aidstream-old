<?php

class Iati_Aidstream_Element_Activity_ContactInfo_Department extends Iati_Core_BaseElement
{
    protected $className = 'Department';
    protected $displayName = 'Department';
    protected $tableName = 'iati_contact_info/department';
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
            if ($this->hasData($data))
            {
                $elementsData = $this->getElementsData($data);

                $elementsData['id'] = '';
                $elementsData['contact_info_id'] = $parentId;
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