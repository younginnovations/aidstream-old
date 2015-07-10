<?php

class Iati_Aidstream_Element_Activity_Sector extends Iati_Core_BaseElement
{

    protected $isMultiple = true;
    protected $className = 'Sector';
    protected $displayName = 'Sector';
    protected $tableName = 'iati_sector';
    protected $attribs = array('id' , 'text' , '@xml_lang' , '@code' , '@percentage' , '@vocabulary');
    protected $iatiAttribs = array('text' , '@xml_lang' , '@vocabulary', '@code' , '@percentage');

    public function saveSector($data , $parentId = null)
    {
        if ($parentId)
        {
            $parentColumnName = $this->getParentCoulmn();
        }
        if ($this->isMultiple)
        {
            foreach ($data as $elementData)
            {
                $elementsData = $this->getElementsData($elementData);
                if ($this->hasData($elementData))
                {
                    if ($parentId)
                    {
                        $elementsData[$parentColumnName] = $parentId;
                    }

                    if(($elementsData['@vocabulary']) && $elementsData['@vocabulary'] != '3')
                    {
                        $elementsData['@code'] = $elementData['non_dac_code'];
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

}