<?php
class Iati_Activity_RecipientCountry
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
            $recipientCountry = new Iati_Activity_RecipientCountry();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $recipientCountry->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $recipientCountry->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            /*case "xml:lang":
                                $recipientCountry->setXmlLang($value);
                                break;*/
                            case "code":
                                $recipientCountry->setCode($value);
                                break;
                            case "percentage":
                                $recipientCountry->setPercentage($value);
                                break;
                                 
                        }
                    }
                }
            }
            $recipient[] = $recipientCountry;
        }
        return $recipient;
    }
}