<?php
class Iati_Activity_RecipientRegion
{
     private $xmlLang = "";
     private $code = "";
     private $percentage = "";
     private $text = "";
     
     public function setXmlLang($xmlLang)
     {
         $this->xmlLang = $xmlLang;
     }
     
     public function setText($text)
     {
         $this->text = $text;
     }
     
     public function setCode($code)
     {
         $this->code = $code;
     }
     
     public function setPercentage($percentage)
     {
         $this->percentage = $percentage;
     }

     public function getXmlLang() {
         return $this->xmlLang;
     }

     public function getCode() {
         return $this->code;
     }

     public function getPercentage() {
         return $this->percentage;
     }

     public function getText() {
         return $this->text;
     }

         public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $recipientRegion = new Iati_Activity_RecipientRegion();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $recipientRegion->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $recipientRegion->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            /*case "xml:lang":
                                $recipientCountry->setXmlLang($value);
                                break;*/
                            case "code":
                                $recipientRegion->setCode($value);
                                break;
                            case "percentage":
                                $recipientRegion->setPercentage($value);
                                break;
                                 
                        }
                    }
                }
            }
            $region[] = $recipientRegion;
        }
        return $region;
    }
}