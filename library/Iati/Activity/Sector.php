<?php
class Iati_Activity_Sector
{
    private $vocabulary = "";
    private $code = "";
    private $percentage = "";
    private $xmlLang = "";
    private $text = "";
    
    public function setVocabulary($type)
    {
        $this->vocabulary = $type;
    }
    
    public function setCode($type)
    {
        $this->code = $type;
    }
    
    public function setPercentage($type)
    {
        $this->percentage = $type;
    }
    
    public function setXmlLang($type)
    {
        $this->xmlLang = $type;
    }
    
    public function setText($type)
    {
        $this->text = $type;
    }

    public function getVocabulary() {
        return $this->vocabulary;
    }

    public function getCode() {
        return $this->code;
    }

    public function getPercentage() {
        return $this->percentage;
    }

    public function getXmlLang() {
        return $this->xmlLang;
    }

    public function getText() {
        return $this->text;
    }

    
    public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $sector = new Iati_Activity_Sector();
            
            $namespaces = $element->getNameSpaces(true);

            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $sector->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $sector->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "vocabulary":
                                $sector->setVocabulary($value);
                                break;
                            case "code":
                                $sector->setCode($value);
                                break;
                            case "percentage":
                                $sector->setPercentage($value);
                                break;
                        }
                    }
                }
            }
            $sectorArray[] = $sector;
        }
        return $sectorArray;
    }
}