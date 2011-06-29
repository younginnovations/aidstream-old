<?php
class Iati_Activity_OtherIdentifier
{
    private $ownerRef = "";
    private $ownerName = "";
    private $text = "";
    
    public function setOwnerRef($type)
    {
        $this->ownerRef = $type;
    }
    
        public function setOwnerName($type)
    {
        $this->ownerName = $type;
    }
    
        public function setText($type)
        {
        $this->text = $type;
    }
    public function getOwnerRef() {
        return $this->ownerRef;
    }

    public function getOwnerName() {
        return $this->ownerName;
    }

    public function getText() {
        return $this->text;
    }

        public static function Process($xml)
    {
        foreach($xml as $element)
        {
            $otherIdentifier = new Iati_Activity_OtherIdentifier();
            
            $element = (array)$element;
            
            foreach($element as $key=>$values)
            {
                if(!is_array($values))
                {
                    $otherIdentifier->setText((string)$values);
                }
                else
                {
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "owner-ref":
                                $otherIdentifier->setOwnerRef("$value");
                                break;
                            
                            case "owner-name":
                                $otherIdentifier->setOwnerName("$value");
                                break;
                        }
                    }
                }
                
            }
            $otherIdentifierArray[] = $otherIdentifier;
        }
        return $otherIdentifierArray;
    }
}
