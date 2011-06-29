<?php
class Iati_Activity_IatiIdentifier
{

    private $text = "";
     
    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }

        public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $identifierObj = new Iati_Activity_IatiIdentifier();
//            $element = (array)$element;
//            foreach ($element as $key=>$values)
//            {

                $identifierObj->setText((string)$element);
//            }
//            $identifier [] = $identifierObj;
        }
        return $identifierObj;
    }
}