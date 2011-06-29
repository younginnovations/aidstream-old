<?php
class Iati_Activity_DefaultFinanceType
{
    private $code = "";
    private $xmlLang = "";
    private $text = "";
    
    function setCode($type)
    {
        $this->code = $type;
    }
    
    function setXmlLang($type)
    {
        $this->xmlLang = $type;
    }
    
    function setText($type)
    {
        $this->text = $type;
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

        function Process($xml)
    {
        foreach($xml as $element)
        {
            $defaultFinanceType = new Iati_Activity_DefaultFinanceType();
            
            $namespaces = $element->getNameSpaces(true);

            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $defaultFinanceType->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $defaultFinanceType->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "code":
                                $defaultFinanceType->setCode($value);
                                break;
                        }
                    }
                }
            }
            $defaultFinanceTypeArray[] = $defaultFinanceType;
        }
        return $defaultFinanceTypeArray;
    }
}