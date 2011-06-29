<?php
class Iati_Activity_DocumentLink
{
    private $url = "";
    private $format = "";
    private $language = "";
    private $category = "";
    private $title = "";
    
    public function setUrl($value)
    {
        $this->url = $value;
    }

    public function setFormat($value)
    {
        $this->format = $value;
    }
    public function setLanguage($value)
    {
        $this->language = $value;
    }

    public function setCategory($value)
    {
        $this->category = $value;
    }
    public function setTitle($value)
    {
        $this->title = $value;
    }
    public function getUrl() {
        return $this->url;
    }

    public function getFormat() {
        return $this->format;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getTitle() {
        return $this->title;
    }

        public static function Process($xmlObject)
    {
//        print_r($xmlObject);exit();
        foreach($xmlObject as $element)
        {
            $documentLink = new Iati_Activity_DocumentLink();
            $attr = $element->attributes();
            $documentLink->setUrl((string)$attr->{'url'});
            $documentLink->setFormat((string)$attr->{'format'});
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

            foreach($element->xpath('language') as $value){
                $document = new stdClass();
                $document->text = (string)$value;
                $documentArray[] = $document;
            }
            $documentLink->setLanguage($documentArray);
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