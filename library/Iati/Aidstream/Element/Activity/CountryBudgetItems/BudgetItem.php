<?php

class Iati_Aidstream_Element_Activity_CountryBudgetItems_BudgetItem extends Iati_Core_BaseElement
{
    protected $className = 'BudgetItem';
    protected $tableName = 'iati_country_budget_items/budget_item';
    protected $isMultiple = true;
    protected $attribs = array('id','@code' , '@percentage');
    protected $iatiAttribs = array('@code' , '@percentage');
    protected $childElements = array('Description');
    protected $viewScriptEnabled = true;

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
                        $elementsData['country_budget_items_id'] = $parentId;
                        $elementsData['@code'] = $elementData['@code'];
                        $elementsData['@percentage'] = $elementData['@percentage'];
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