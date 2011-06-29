<?php
class Iati_Activity_Location
{
    private $percentage = "";
    private $location_type = "";
    private $name = "";
    private $description = "";
    private $administrative = "";
    private $coordinates = "";
    private $gazetteer_entry = "";
    
    public function setPercentage($value)
    {
        $this->percentage = $value;
    }

    public function setLocationType($value)
    {
        $this->location_type = $value;
    }
    public function setName($value)
    {
        $this->name = $value;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }
    public function setAdministrative($value)
    {
        $this->administrative = $value;
    }
 public function setCoordinates($value)
    {
        $this->coordinates = $value;
    }
 public function setGazetteerEntry($value)
    {
        $this->gazetteer_entry = $value;
    }

    public function getPercentage() {
        return $this->percentage;
    }

    public function getLocation_type() {
        return $this->location_type;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAdministrative() {
        return $this->administrative;
    }

    public function getCoordinates() {
        return $this->coordinates;
    }

    public function getGazetteer_entry() {
        return $this->gazetteer_entry;
    }

        public static function Process($xmlObject)
    {
        print_r($xmlObject);exit();
        foreach($xmlObject as $element)
        {
            $location = new Iati_Activity_Location();
            $attr = $element->attributes();
            $location->setPercentage((string)$attr->{'percentage'});
            /*$namespaces = $element->getNameSpaces(true);
             if($namespaces){
             $xm = $element->children($namespaces['xml']);
             $xm = (array)$xm;
             if($xm['@attributes']['lang']){
             $contactInfo->setXmlLang($xm['@attributes']['lang']);
             }
             }*/
            /* $transactionTypeArray = array();
             foreach($element->xpath('transaction-type') as $value){

             $attr = $value->attributes();
             $transaction_type = new stdClass();
             $transaction_type->code = (string)$attr->{'code'};
             $transaction_type->text = (string)$value;
             $transactionTypeArray[] = $transaction_type;
             }
             $transaction->setTransactionType($transactionTypeArray);*/

            foreach($element->xpath('locatiion-type') as $value){
                $location->language->text = (string)$value;
            }
            //            print_r($transaction);exit();
            $categoriesArray = array();
            foreach($element->xpath('category') as $value){
                $attr = $value->attributes();
                $categories = new stdClass();
                $categories->code = (string)$attr->{'code'};
                $categories->text = (string)$value;
                $categoriesArray[] = $categories;
            }
            $documentLink->setCategory($categoriesArray);
            //            print_r($transaction);exit();
            $titleArray = array();
            foreach($element->xpath('title') as $value){
                $title->text = (string)$value;
                $titleArray[] = $title;
            }
            $documentLink->setTitle($titleArray);
            
            $documentLinkArray[] = $documentLink;
        }
        return $documentLinkArray;
    }

}