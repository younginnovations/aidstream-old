<?php
class Iati_Activity_PolicyMarker {
    private $significance = "";
    private $vocabulary = "";
    private $code = "";
    private $xmlLang = "";
    private $text = "";

    public function setSignificance($type)
    {
        $this->significance = $type;
    }
    
    public function setVocabulary($type)
    {
        $this->vocabulary = $type;
    }
    
    public function setCode($type)
    { 
        $this->code = $type;
    }
    
    public function setXmlLang($type)
    {
        $this->xmlLang = $type;
    }
    
    public function setText($type)
    {
        $this->text = $type;
    }

    public function getSignificance() {
        return $this->significance;
    }

    public function getVocabulary() {
        return $this->vocabulary;
    }

    public function getCode() {
        return $this->code;
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
            $policyMarker = new Iati_Activity_PolicyMarker();
            
            $namespaces = $element->getNameSpaces(true);

            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $policyMarker->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $policyMarker->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "significance":
                                $policyMarker->setSignificance($value);
                                break;
                            case "vocabulary":
                                $policyMarker->setVocabulary($value);
                                break;
                            case "code":
                                 $policyMarker->setCode($value);
                                 break;
                        }
                    }
                }
            }
            $policyMarkerArray[] = $policyMarker;
        }
        return $policyMarkerArray;
    }
}