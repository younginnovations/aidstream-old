<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Language extends Iati_Core_BaseElement
{

    protected $isMultiple = true;
    protected $className = 'Language';
    protected $displayName = 'Language';
    protected $attribs = array('id' , 'text');
    protected $iatiAttribs = array('text');
    protected $tableName = 'iati_document_link/language';
    
    protected function generateElementXml($elementData , $parent)
    {
        $eleName = $this->getXmlName();
        $data = $this->getElementsIatiData($elementData);
        
        if(!$this->hasData($data) && empty($this->childElements)) return;  //Donot generate xml if no iati data and no child
                
        if ($data['text'])
        {
            $data['text'] = Iati_Core_Codelist::getCodeByAttrib($this->className , '@xml_lang' , $data['text']);
        } else {
            return;
        }
        
        $xmlObj = $parent->addChild($eleName , preg_replace('/&(?!\w+;)/' , '&amp;' ,$data['text']));
        
        if($this->hasData($data)){
            $xmlObj = $this->addElementsXmlAttribsFromData($xmlObj , $data);
        }
        
        return $xmlObj;
    }

}