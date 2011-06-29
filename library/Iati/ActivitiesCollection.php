<?php
class Iati_ActivitiesCollection
{
    protected $IATIActivities = array();
    protected $xmlPath = null;
    protected $dbResult = null;

    public function setIATIActivities($IATIActivities)
    {
        $this->IATIActivities = $IATIActivities;
    }


    public function setXmlPath($xmlPath){
        $this->xmlPath = $xmlPath;
    }

    public function setDbResult($dbResult)
    {
        $this->dbResult = $dbResult;
    }

    public function getIATIActivities()
    {
        return $this->IATIActivities;
    }

    public function getXmlPath()
    {
        return $this->xmlPath;
    }

    public function getDbResult()
    {
        return $this->dbResult;
    }

    public function process()
    {
        if($this->xmlPath){
            $xmlString = file_get_contents($this->xmlPath);
//            $IATIActivity = new Iati_Viewer_IATIStringProcessor($xmlString);
//            print_r($xmlArray);exit();
            $stringProcessor = new Iati_StringProcessor($xmlString);
            $this->IATIActivities['attributes'] = array('version'=>(string)$stringProcessor->getVersion(), 'generated-datetime'=>(string)$stringProcessor->getGeneratedDateTime());
            $this->IATIActivities['activities'] = $stringProcessor->getActivityObject();
            return $this->IATIActivities;
//            print_r($this->IATIActivities);exit();
            /*foreach($xmlArray as $key=>$eachArray){
//                print_r($eachArray);exit();
//                $iatiActivity = new Iati_Viewer_IATIXmlObjectProcessor();
                $this->IATIActivities[] = new Iati_StringProcessor($eachArray);
            }exit();*/

        }
        elseif($this->dbResult){
            print "dbResult";
        }
        else{
            return false;
        }
    }


}