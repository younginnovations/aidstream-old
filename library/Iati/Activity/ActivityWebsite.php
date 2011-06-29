<?php
class Iati_Activity_ActivityWebsite
{
    private $text = "";

    
    public function setText($value)
    {
        $this->text = $value;
    }

    public function getText() {
        return $this->text;
    }

        public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $activity_website = new Iati_Activity_ActivityWebsite();
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $activity_website->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $activity_website->setText($values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            /*case "type":
                                $descriptionObj->setType($value);
                                break;*/
                        }
                    }
                }
            }
            $activity_websiteArray[] = $activity_website;
        }
        return $activity_websiteArray;
    }
}