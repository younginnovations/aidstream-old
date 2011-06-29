<?php
class Iati_Activity_ActivityStatus
{
    private $code = "";
    private $xmlLang = "";
    private $text = "";


    public function setCode($value){
        $this->code = $value;
    }
    
    public function setXmlLang($value){
        $this->xmlLang = $value;
    }
    public function setText($value){
        $this->text = $value;
    }
    /*$e->setIsoDate("2011-01-02");
     $e->setXmlLang('en');
     $e->setText("2011");*/

  public function getCode() {
      return $this->code;
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
            $activityStatus = new Iati_Activity_ActivityStatus();

            $namespaces = $element->getNameSpaces(true);
            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $activityStatus->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $activityStatus->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "code":
//                                print "dd";exit();
                                $activityStatus->setCode($value);
                                break;

                                 
                        }
                    }
                }
            }
            $activityStatusArray[] = $activityStatus;
        }
//        print_r($activityDateArray);exit();
        return $activityStatusArray;
    }

}