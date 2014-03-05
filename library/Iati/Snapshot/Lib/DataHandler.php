<?php
require_once(__dir__."/../Config.php");

class Iati_Snapshot_Lib_DataHandler
{
    protected $json;
    protected $reportingOrg;
    protected $filename = '';

    public function __construct($reportingOrg = 'all')
    {
        $this->setNewReportingOrg($reportingOrg);
    }

    public function setNewReportingOrg($reportingOrg)
    {
        $this->reportingOrg = $reportingOrg;
        $this->getJsonData();
    }

    public function getJsonData()
    {
        $repOrgName = preg_replace('/-| /' , '_' , strtolower($this->reportingOrg));
        $this->filename = INPUT_JSON_DIR.$repOrgName.".json";
        if(file_exists($this->filename)) {
            $fp = fopen( $this->filename, 'r');
            $this->json = fread($fp , filesize($this->filename));
            fclose($fp);

            if($this->json){
                $this->data = json_decode($this->json);
            }
        }
        return $this->json;
    }

    public function getData()
    {
        if($this->data){
            return $this->data;
        }
        return false;
    }

    public function get($elementName , $ajax = false)
    {
        if($this->getData()){
            if($ajax){
                return json_encode($this->getData()->$elementName);
            }
            return $this->getData()->$elementName;
        }
        return false;
    }

    /*
    public function getActivityStatusDetails()
    {
        if($this->getData()){
            return $this->getData()->activity_status;
        }
        return false;
    }
    */
    public function getActivityDatesDetails($type = '')
    {
        if($this->getData()){
            $acitvityDates = $this->get('activity_dates');
            if($type){
                return $acitvityDates->$type;
            }
            return $acitvityDates;
        }
        return false;
    }

    public function getTop3Sectors()
    {
        if($this->getData()){
            $sectors = $this->get('sectors');
            if(!$sectors) return false;
            $sectorArray = array();
            foreach($sectors as $sectorName=>$sector){
                $sectorArray[$sectorName] = $sector->count;
            }
            arsort($sectorArray);
            $output = array_slice($sectorArray ,0 , 3);
            return $output;
        }
        return false;
    }

    public function getTop3Activities()
    {
        if($this->getData()){
            $activities = $this->get('activities');
            if(!$activities) return false;
            $activityArray = array();
            foreach($activities as $activityId => $activityTitle){
                $activityArray[$activityId] = $activityTitle;
            }
            arsort($activityArray);
            $output = array_slice($activityArray, 0, 3);
            return $output;
        }
        return false;
    }
}