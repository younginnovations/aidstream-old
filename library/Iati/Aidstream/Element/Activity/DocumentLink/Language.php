<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Language extends Iati_Core_BaseElement
{

    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Language';
    protected $displayName = 'Language';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_document_link/language';

    protected function generateElementXml($elementData , $parent)
    {
        $eleName = $this->getXmlName();
        $data = $this->getElementsIatiData($elementData);

        if(!$this->hasData($data) && empty($this->childElements)) return;  //Donot generate xml if no iati data and no child
        
        // Get language value from code
        if ($data['@code']) {
            $data['@code'] = Iati_Core_Codelist::getCodeByAttrib($this->className , '@xml_lang' , $data['@code']);
        } else {
            return;
        }

        $xmlObj = $parent->addChild($eleName);
        $xmlObj = $xmlObj->addAttribute('code', $data['@code']);
        
        return $xmlObj;
    }
}