<?php
class Iati_Activity_ReportingOrg
{
    private $xmlLang = "";
    private $ref = "";
    private $text = "";
    private $type = "";

    public function setXmlLang($xmlLang)
    {
        $this->xmlLang = $xmlLang;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getXmlLang() {
        return $this->xmlLang;
    }

    public function getRef() {
        return $this->ref;
    }

    public function getText() {
        return $this->text;
    }

    public function getType() {
        return $this->type;
    }

    
    public static function Process($reportingObject)
    {
        //            print_r($reportingObject);exit();
        foreach ($reportingObject as $element){
            $reportingOrg = new Iati_Activity_ReportingOrg();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $reportingOrg->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $reportingOrg->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "ref":
                                $reportingOrg->setRef($value);
                                break;
                            case "type":
                                $reportingOrg->setType($value);
                                break;
                        }
                    }
                }
            }
            $reportingArray[] = $reportingOrg;
        }
        return $reportingArray;
    }
}