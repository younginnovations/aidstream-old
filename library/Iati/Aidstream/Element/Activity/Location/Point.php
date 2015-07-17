<?php

class Iati_Aidstream_Element_Activity_Location_Point extends Iati_Core_BaseElement
{   
    protected $className = 'Point';
    protected $displayName = 'Point';
    protected $tableName = 'iati_location/point';
    protected $attribs = array('id', '@srsName');
    protected $iatiAttribs = array('@srsName');
    protected $childElements = array('Pos');

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
                $elementsData['location_id'] = $parentId;
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