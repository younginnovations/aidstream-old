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
            if ($parentId)
            {
                $parentColumnName = $this->getParentCoulmn();
            }
            if ($this->isMultiple)
            {   
                foreach ($data as $elementData)
                {
                    $model = new Model_Wep();
                    $vocabData = $model->getRowsByFields('iati_country_budget_items', 'id', $parentId);
                    $elementsData = $this->getElementsData($elementData);
                    if ($this->hasData($elementData))
                    {
                        if ($parentId)
                        {
                            $elementsData[$parentColumnName] = $parentId;
                        }

                        if(($vocabData[0]['@vocabulary']) && $vocabData[0]['@vocabulary'] != '1')
                        {
                            $elementsData['@code'] = $elementData['non_iati'];
                        }
                        // If no id is present, insert the data else update the data using the id.
                        if (!$elementsData['id'])
                        {
                            $elementsData['id'] = null;
                            $eleId = $this->db->insert($elementsData);
                        } else
                        {
                            $eleId = $elementsData['id'];
                            unset($elementsData['id']);
                            $this->db->update($elementsData , array('id = ?' => $eleId));
                        }
                    } else
                    {
                        if ($elementData['id'])
                        {
                            $where = $this->db->getAdapter()->quoteInto('id = ?' , $elementData['id']);
                            $this->db->delete($where);
                            return;
                        }
                    }

                    // If children are present create children elements and call their save function.
                    if (!empty($this->childElements))
                    {
                        foreach ($this->childElements as $childElementClass)
                        {
                            $childElementName = get_class($this) . "_$childElementClass";
                            $childElement = new $childElementName();
                            $childElement->save($elementData[$childElement->getClassName()] , $eleId);
                        }
                    }
                }
            }
            return $eleId;
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