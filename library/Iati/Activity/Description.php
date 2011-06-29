<?php
class Iati_Activity_Description
{

    private $xmlLang = "";
    private $type = "";
    private $text = "";

    public function setXmlLang($value)
    {
        $this->xmlLang = $value;
    }

    public function setType($value)
    {
        $this->type = $value;
    }
    public function setText($value)
    {
        $this->text = $value;
    }

    public function getXmlLang() {
        return $this->xmlLang;
    }

    public function getType() {
        return $this->type;
    }

    public function getText() {
        return $this->text;
    }

        public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $descriptionObj = new Iati_Activity_Description();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $descriptionObj->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $descriptionObj->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "type":
                                $descriptionObj->setType($value);
                                break;
                        }
                    }
                }
            }
            $description[] = $descriptionObj;
        }
        return $description;
    }
}