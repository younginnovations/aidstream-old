<?php

class Iati_Aidstream_Element_Activity_Conditions_Condition extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Condition';
    protected $displayName = 'Condition';
    protected $tableName = 'iati_conditions/condition';
    protected $attribs = array('id', '@type');
    protected $iatiAttribs = array('@type');
    protected $childElements = array('Narrative');

    public function save($data , $parentId = null, $duplicate = false)
    {
        if(!$duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {

            if ($this->isMultiple)
            {
                foreach ($data as $elementData)
                {
                    if ($this->hasData($elementData))
                    {
                        $elementsData = $this->getElementsData($elementData);

                        $elementsData['id'] = '';
                        $elementsData['conditions_id'] = $parentId;
                        $elementsData['@type'] = $elementData['@type'];
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