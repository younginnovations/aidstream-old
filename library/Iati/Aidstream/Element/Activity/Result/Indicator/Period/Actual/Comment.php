<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_Actual_Comment extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Comment';
    protected $displayName = 'Comment';
    protected $tableName = 'iati_result/indicator/period/actual/comment';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    protected $viewScriptEnabled = false;

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
                        $elementsData['actual_id'] = $parentId;
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