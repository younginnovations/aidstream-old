<?php
class Iati_Activity_ActivityDate
{
    private $type = "";
    private $isoDate = "";
    private $xmlLang = "";
    private $text = "";


    public function setType($type){
        $this->type = $type;
    }
    public function setIsoDate($type){
        $this->isoDate = $type;
    }
    public function setXmlLang($type){
        $this->xmlLang = $type;
    }
    public function setText($type){
        $this->text = $type;
    }
    /*$e->setIsoDate("2011-01-02");
     $e->setXmlLang('en');
     $e->setText("2011");*/

  public function getType() {
      return $this->type;
  }

  public function getIsoDate() {
      return $this->isoDate;
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
            $activityDate = new Iati_Activity_ActivityDate();

            $namespaces = $element->getNameSpaces(true);

            if($namespaces){
                $xm = $element->children($namespaces['xml']);
                $xm = (array)$xm;
                if($xm['@attributes']['lang']){
                    $activityDate->setXmlLang($xm['@attributes']['lang']);
                }
            }
            $element = (array)$element;
            foreach ($element as $key=>$values)
            {
                if(!is_array($values)){
                    $activityDate->setText((string)$values);
                }else{
                    foreach($values as $key=>$value)
                    {
                        switch($key)
                        {
                            case "iso-date":
                                $activityDate->setIsoDate($value);
                                break;
                            case "type":
//                                print "dd";exit();
                                $activityDate->setType($value);
                                break;

                                 
                        }
                    }
                }
            }
            $activityDateArray[] = $activityDate;
        }
//        print_r($activityDateArray);exit();
        return $activityDateArray;
    }

}