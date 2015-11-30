<?php

class Iati_Aidstream_Element_Activity_Result_Indicator extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Indicator';
    protected $displayName = 'Indicator';
    protected $tableName = 'iati_result/indicator';
    protected $attribs = array('id', '@measure', '@ascending');
    protected $iatiAttribs = array('@measure', '@ascending');
    protected $childElements = array('Title', 'Description', 'Baseline', 'Period');
    protected $viewScriptEnabled = false;
    protected $isRequired = true;

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
                        $elementsData['result_id'] = $parentId;
                        $elementsData['@measure'] = $elementData['@measure'];
                        $elementsData['@ascending'] = $elementData['@ascending'];
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