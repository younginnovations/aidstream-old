<?php

class Iati_Aidstream_Element_Activity_ContactInfo_MailingAddress extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'MailingAddress';
    protected $displayName = 'Mailing Address';
    protected $tableName = 'iati_contact_info/mailing_address';
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
            if ($this->isMultiple)
            {
                foreach ($data as $elementData)
                {
                    if ($this->hasData($elementData))
                    {
                        $elementsData = $this->getElementsData($elementData);

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
                            $childElement->save($elementData[$childElement->getClassName()] , $eleId, true);
                        }
                    }
                }
            }
            return $eleId;
        }
    }
}