<?php
class Iati_Activity_Title
{
     private $xmlLang = "";
     private $text = "";
     
     public function setXmlLang($xmlLang)
     {
         $this->xmlLang = $xmlLang;
     }
     
     public function setText($text)
     {
         $this->text = $text;
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
            $titleObj = new Iati_Activity_Title();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $titleObj->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $titleObj->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                                 
                        }
                    }
                }
            }
            $title[] = $titleObj;
        }
        return $title;
    }
}