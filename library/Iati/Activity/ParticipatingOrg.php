<?php
class Iati_Activity_ParticipatingOrg
{
    
    private $xmlLang = "";
    private $ref = "";
    private $text = "";
    private $role = "";
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
    
    public function setRole($role)
    {
        $this->role = $role;
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

    public function getRole() {
        return $this->role;
    }

    public function getType() {
        return $this->type;
    }

        public static function Process($xmlObject)
    {
        foreach($xmlObject as $element)
        { 
            $participatingOrg = new Iati_Activity_ParticipatingOrg();
            
            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                    $xm = $element->children($namespaces['xml']);
                    $xm = (array)$xm;
                    if($xm['@attributes']['lang']){
                        $participatingOrg->setXmlLang($xm['@attributes']['lang']);
                    }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $participatingOrg->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "ref":
                                $participatingOrg->setRef($value);
                                break;
                            case "type":
                                $participatingOrg->setType($value);
                                break;
                            case "role":
                                $participatingOrg->setRole($value);
                                break;
                                 
                        }
                    }
                }
            }
            $participating[] = $participatingOrg;
        } 
       return $participating;
    }
    
}