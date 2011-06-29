<?php
class Iati_Activity_ContactInfo
{

    private $organisation = "";
    private $person_name = "";
    private $telephone = "";
    private $email = "";
    private $mailing_address = "";

    public function setOrganisation($value)
    {
        $this->organisation = $value;
    }
    public function setPersonName($value)
    {
        $this->person_name = $value;
    }
    public function setTelephone($value)
    {
        $this->telephone = $value;
    }
    public function setEmail($value)
    {
        $this->email = $value;
    }
    public function setMailingAddress($value)
    {
        $this->mailing_address = $value;
    }
    public function getOrganisation() {
        return $this->organisation;
    }

    public function getPerson_name() {
        return $this->person_name;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMailing_address() {
        return $this->mailing_address;
    }

        public static function Process($xmlObject)
    {
        //        print_r($xmlObject);exit();
        foreach($xmlObject as $element)
        {
            $contactInfo = new Iati_Activity_ContactInfo();

            /*$namespaces = $element->getNameSpaces(true);
             if($namespaces){
             $xm = $element->children($namespaces['xml']);
             $xm = (array)$xm;
             if($xm['@attributes']['lang']){
             $contactInfo->setXmlLang($xm['@attributes']['lang']);
             }
             }*/
            $element = (array)$element;

            foreach ($element as $key=>$values)
            {
                switch($key)
                {
                    case "organisation":
                        $contactInfo->setOrganisation($values);
                        break;
                    case "person-name":
                        $contactInfo->setPersonName($values);
                        break;
                    case "telephone":
//                        var_dump($values);exit();
                        $values = (array)$values;
                        foreach($values as  $val){
                            $telephone[] = $val;
                        }
                        $contactInfo->setTelephone($telephone);
                        break;
                    case "email":
                        $contactInfo->setEmail($values);
                        break;
                        
                    case "mailing-address":
                        $contactInfo->setMailingAddress($values);
                }


            }
            $contactInfoArray[] = $contactInfo;
        }
        return $contactInfoArray;
    }

}