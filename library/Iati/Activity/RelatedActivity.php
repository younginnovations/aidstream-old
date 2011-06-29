<?php
class Iati_Activity_RelatedActivity
{
    private $type = "";
    private $ref = "";
    private $xmlLang = "";
    private $text = "";
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function setRef($type)
    {
        $this->ref = $type;
    }
    
    public function setXmlLang($type)
    {
        $this->xmlLang = $type;
    }
    
    public function setText($type)
    {
        $this->text = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function getRef() {
        return $this->ref;
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
            $relatedActivity = new Iati_Activity_RelatedActivity();
            
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $relatedActivity->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $relatedActivity->setText((string)$values);
                }
                else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "type":
                                $relatedActivity->setType($value);
                                break;
                            case "ref":
                                $relatedActivity->setRef($value);
                                break;
                        }
                    }
                }
            }
            $relatedActivityArray[] = $relatedActivity;
        }
        return $relatedActivityArray;
    }
}