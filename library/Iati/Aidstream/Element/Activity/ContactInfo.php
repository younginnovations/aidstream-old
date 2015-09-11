<?php

class Iati_Aidstream_Element_Activity_ContactInfo extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'ContactInfo';
    protected $displayName = 'Contact Info';
    protected $tableName = 'iati_contact_info';
    protected $attribs = array('id' , '@type' , '@xml_lang');
    protected $iatiAttribs = array('@type' , '@xml_lang');
    protected $childElements = array('Organisation','PersonName' , 'JobTitle' , 'Telephone','Email','MailingAddress' , 'Website');

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
            if ($this->isMultiple)
            {
                foreach ($data as $elementData)
                {
                    if ($this->hasData($elementData))
                    {
                        $elementsData = $this->getElementsData($elementData);
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
                            $childElement->save($elementData[$childElement->getClassName()] , $eleId, true);
                        }
                    }
                }
            }
            return $eleId;
        }
    }
}

