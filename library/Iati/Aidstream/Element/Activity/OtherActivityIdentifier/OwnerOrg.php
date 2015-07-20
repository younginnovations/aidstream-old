<?php

class Iati_Aidstream_Element_Activity_OtherActivityIdentifier_OwnerOrg extends Iati_Core_BaseElement
{
    protected $className = 'OwnerOrg';
    protected $displayName = 'Owner Org';
    protected $tableName = 'iati_other_identifier/ownerorg';
    protected $attribs = array('id','@ref');
    protected $iatiAttribs = array('@ref');
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
                $elementsData['other_activity_identifier_id'] = $parentId;
                $elementsData['@ref'] = $data['@ref'];
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