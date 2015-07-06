<?php
error_reporting(1);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

define('APPLICATION_ENV', 'production');
    
    
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library/Zend/Db/Adapter/Mysqli'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Loader/Autoloader.php';
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Iati_');

$application->getBootstrap()->bootstrap(array('db'));

include('../application/modules/default/models/ActivityCollection.php');

class Model_ActivityCollectionNew extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';

    public function getAllActivities()
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'iact.id')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','');
        return $this->fetchAll($rowSet)->toArray();
    }

    public function getOrg($activity_id)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('acc'=>'account'),'*')
            ->join(array('ias'=>'iati_activities'), 'acc.id = ias.account_id', '*' )
            ->join(array('a'=>'iati_activity'), 'ias.id = a.activities_id', '*')
            ->join(array('user'=>'user'), 'acc.id = user.account_id', '*')
            ->where('a.id=?',$activity_id);
        return $activities = $this->fetchAll($rowSet)->toArray();
    }    
}

function getActivityIds() {
	$ids = array();
	$actModel = new Model_ActivityCollectionNew();

	$activityIds = $actModel->getAllActivities();
	foreach($activityIds as $id){
	       $ids[] = $id['id'];
	}
	return $ids;
}

function getElementData($activitiesId , $elementClass)
{
  global $autoloader;
  if(empty($activitiesId)) return;
  $eleData = array();
  $className = "Iati_Aidstream_Element_".$elementClass;
  $autoloader->autoload($className);
  $eleObj = new $className();
  
  if($elementClass == 'Activity'){
       $parent = false;
  } else {
       $parent = true;
  }
  
  
  foreach($activitiesId as $activityId){
       $data = array();
       $elementData = $eleObj->fetchData($activityId , $parent);
       if(!empty($elementData)){
            if($parent){
                 $eleData = array_merge($eleData , $elementData);
            } else {
                 $eleData[] = $elementData;
            }
       }
  } 

  return $eleData;
}

function prepareLocationDataForCompleteDownload($activitiesData)
{
      $element = new Iati_Aidstream_Element_Activity();
      $element->setIsMultiple(false);
      
      $csvData = array();
      foreach($activitiesData as $activityData){
        $act['activity_id'] = (string)$activityData['Activity']['id'];
        $act['organization_name'] = "";
        $act['organization_email'] = "";

        $actModel = new Model_ActivityCollectionNew();
        $id = (string)$activityData['Activity']['id'];
        $a = $actModel->getOrg($id);
        // var_dump($a);exit();
        foreach ($a as $org) {
          $act['organization_name'] = $org['name'];
          $act['organization_email'] = $org['email'];
        }

        /* for location */
        $act['percentage'] = "";
        $act['location_type_code'] = "";
        $act['location_text'] = "";
        $act['location_name'] = "";
        $act['description'] = "";
        $act['country_code'] = "";
        $act['admin1'] = "";
        $act['admin2'] = "";
        $act['administrative_text'] = "";
        $act['latitude'] = "";
        $act['longitude'] = "";
        $act['precision'] = "";
        foreach ($activityData['Activity']['Location'] as $location) {
          $act['percentage'] = $location['@percentage'];
          $act['location_type_code'] = $location['LocationType']['@code'];
          $act['location_text'] = $location['LocationType']['text'];
          $act['country_code'] = $location['Administrative']['@country'];
          $act['admin1'] = $location['Administrative']['@adm1'];
          $act['admin2'] = $location['Administrative']['@adm2'];
          $act['administrative_text'] = $location['Administrative']['text'];
          $act['latitude'] = $location['Coordinates']['@latitude'];
          $act['longitude'] = $location['Coordinates']['@longitude'];
          $act['precision'] = $location['Coordinates']['@precision'];
        }
        foreach ($activityData['Activity']['Location'] as $loc){
          foreach ($loc['Name'] as $name) {
            $act['location_name'] = $name['text'];
          }
        }
        foreach ($activityData['Activity']['Location'] as $loca){
          foreach ($loca['Description'] as $des) {
            $act['description'] = $name['des'];
          }
        }
        $act['gazetteer_ref_agency'] = "";
        $act['gazetteer_text'] = "";
        foreach ($activityData['Activity']['Location'] as $locate){
          foreach ($locate['GazetteerEntry'] as $gaz) {
            $act['gazetteer_ref_agency'] = (string)$gaz['@gazetteer_ref'];
            $act['gazetteer_text'] = (string)$gaz['text'];
          }
        }
        $csvData[] = $act;

      }
      
      return $csvData;
}

function prepareAllDataForCompleteDownload($activitiesData)
{
      $element = new Iati_Aidstream_Element_Activity();
      $element->setIsMultiple(false);
      
      $csvData = array();
      foreach($activitiesData as $activityData){
        $act['activity_id'] = (string)$activityData['Activity']['id'];
        $act['organization_name'] = "";
        $act['organization_email'] = "";

        $actModel = new Model_ActivityCollectionNew();
        $id = (string)$activityData['Activity']['id'];
        $a = $actModel->getOrg($id);
        // var_dump($a);exit();
        foreach ($a as $org) {
          $act['organization_name'] = $org['name'];
          $act['organization_email'] = $org['email'];
        }

        $act['default_currency'] = $activityData['Activity']['@default_currency'];
            $act['default_language'] = $activityData['Activity']['@xml_lang'];
            $act['default_currency'] = $activityData['Activity']['@default_currency'];
            $act['last_updated'] = $activityData['Activity']['@last_updated_datetime'];
            $act['reporting_organization'] = $activityData['Activity']['ReportingOrg']['text'];
            $act['reporting_organization_ref'] = $activityData['Activity']['ReportingOrg']['@ref'];
            $act['reporting_organization_type'] = $activityData['Activity']['ReportingOrg']['@type'];
            $act['iati_identifier'] = $activityData['Activity']['IatiIdentifier']['text'];

            $act['title'] = "";
            foreach ($activityData['Activity']['Title'] as $title) {
              $act['title'] = $title['text'];
            }
            
            if(((string)($activityData['Activity']['status_id'])) == 1){
              $act['activity_state'] = "[1]Draft";
            }
            else if(((string)($activityData['Activity']['status_id'])) == 2){
              $act['activity_state'] = "[2]Completed";
            }
            else if(((string)($activityData['Activity']['status_id'])) == 3){
              $act['activity_state'] = "[3]Verified";
            }
            else if(((string)($activityData['Activity']['status_id'])) == 4){
              $act['activity_state'] = "[4]Published";
            }
            else{
              $act['activity_state'] = "[1]Draft";
            }
             
            $desc = array(); 
            $description_type = array();
            foreach ($activityData['Activity']['Description'] as $description) {
              $desc[] = $description['text'];

              if($description['@type'] == 1){
                $description_type[] = "[1]General";
              }
              if($description['@type'] == 2){
                $description_type[] = "[2]Objectives";
              }
              if($description['@type'] == 3){
                $description_type[] = "[3]Target Groups";
              }
              if($description['@type'] == 4){
                $description_type[] = "[4]Other";
              }
            }
            $act['description'] = implode(';', $desc);
            $act['description_type'] = implode(';', $description_type);
            
            $act['activity_status'] = $activityData['Activity']['ActivityStatus']['@code'];

              $act['start_planned'] = "";
              $act['end_planned'] = "";
              $act['start_actual'] = "";
              $act['end_actual'] = "";
            foreach($activityData['Activity']['ActivityDate'] as $activityDate) {
              
              if($activityDate['@type'] == 1) {
                    $act['start_planned'] = $activityDate['@iso_date'];
              }
              
              if($activityDate['@type'] == 2) {
                    
                    $act['end_planned'] = $activityDate['@iso_date'];
              }
              

              if($activityDate['@type'] == 3) {
                  
                    $act['start_actual'] = $activityDate['@iso_date'];
              }              
               
              if($activityDate['@type'] == 4) {
                    $act['end_actual'] = $activityDate['@iso_date'];
              }
            }

            $act['funding_organisations'] = "";
            $act['extending_organisations'] = "";
            $act['accountable_organisations'] = "";
            $act['implementing_organisations'] = "";
            foreach($activityData['Activity']['ParticipatingOrg'] as $partOrg)
            {
              if($partOrg['@role'] == 1) {
                    $act['funding_organisations'] = $partOrg['text'];
              }
              
              if($partOrg['@role'] == 2) {
                    $act['extending_organisations'] = $partOrg['text'];
              }
              
              if($partOrg['@role'] == 3) {
                    $act['accountable_organisations'] = $partOrg['text'];
              }
              
              if($partOrg['@role'] == 4) {
                    $act['implementing_organisations'] = $partOrg['text'];
              }
              
            }  

            $act['recipient_country'] = "";
            $act['recipient_country_code'] = "";
            $act['recipient_country_percentage'] = "";
            foreach ($activityData['Activity']['RecipientCountry'] as $repcientCountry) {
              $act['recipient_country'] = $repcientCountry['text'];
              $act['recipient_country_code'] = $repcientCountry['@code'];
              $act['recipient_country_percentage'] = $repcientCountry['@percentage'];
            }
            
            $act['recipient_region'] = "";
            $act['recipient_region_code'] = "";
            $act['recipient_region_percentage'] = "";
            foreach ($activityData['Activity']['RecipientRegion'] as $recipientRegion) {
              $act['recipient_region'] = $recipientRegion['text'];
              $act['recipient_region_code'] = $recipientRegion['@code'];
              $act['recipient_region_percentage'] = $recipientRegion['@percentage'];
            }

            $act['sector_text'] = "";
            $act['sector_vocabulary'] = "";
            $act['sector_codes'] = "";
            $act['sector_percentage'] = "";
            foreach ($activityData['Activity']['Sector'] as $sector) {
              $act['sector_text'] = $sector['text'];
              $act['sector_vocabulary'] = $sector['@vocabulary'];
              $act['sector_codes'] = $sector['@code'];
              $act['sector_percentage'] = $sector['@percentage'];
            }
            
            $act['contact_info_text'] = "";
            foreach ($activityData['Activity']['ContactInfo'] as $contact) {
              $act['contact_info_text'] = $contact['@type'];
                           
            }
            
        $csvData[] = $act;

      }
      
      return $csvData;
} 


  function downloadArrayToCsv($data , $options = null)
{
  $filename = 'output';
  $enclosure = '"';
  $seperator = ',';
  $lineBreak = "\r\n";

  // Set filename
  if(isset($options['filename'])){
          $filename = $options['filename'];
  }
  // Set enclosure
  if(isset($options['enclosure'])){
          $enclosure = $options['enclosure'];
  }
  // set seperator
  if(isset($options['seperator'])){
          $seperator = $options['seperator'];
  }

  $csvOutput = '';
  foreach($data as $row){
          foreach($row as $value){
                $value = preg_replace('/\s+/', ' ', trim($value));
                $value = str_replace('"', '\'', $value);
                $csvOutput .= $enclosure.$value.$enclosure.$seperator;
          }
          $csvOutput .= $lineBreak;
  }

  return $csvOutput;
}

function addHeaderFromKeys($data)
{
 if(empty($data)) return;
 
 $keys = array_keys($data[0]);
 $dataWithHeader = array_unshift($data , $keys);
 return $data;
}

$activitiesId = getActivityIds();
$activitiesData = getElementData($activitiesId, 'Activity');
$csvLocationData = prepareLocationDataForCompleteDownload($activitiesData);
$csvAllData = prepareAllDataForCompleteDownload($activitiesData);

$csvAllDataWithHeader = addHeaderFromKeys($csvAllData);
$csvLocationDataWithHeader = addHeaderFromKeys($csvLocationData);

$fp = fopen('Location.csv', 'w');

foreach ($csvLocationDataWithHeader as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);

$fap = fopen('complete.csv', 'w');

foreach ($csvAllDataWithHeader  as $all) {
	fputcsv($fap, $all);
}

fclose($fap);
