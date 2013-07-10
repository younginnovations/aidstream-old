<?php
DEFINE ('VDCS_DEFAULT_VALUE' , 'District');
DEFINE ('LOCATION_API' , "http://www.developmentcheck.org/geotag/");

class Simplified_Model_Simplified
{
    public $activityId;
    public $model;
    public $defaults;
    
    public function __construct()
    {
        $this->model = new Model_Wep();
    }
    
    public static function isSimplified()
    {
        $ses = new Zend_Session_Namespace('simplified');
        if($ses->simplified){
            return true;
        } else {
            return false;
        }
        
    }
    /**
     *Function to save activity
     *
     * @todo break this function to smaller functions.
     */
    public function addActivity($data , $default)
    {
        $this->defaults = $default;
        $identity = Zend_Auth::getInstance()->getIdentity();
        //var_dump($data);exit;
        $model = $this->model;
        $modelActivity =  new Model_Activity();
        
        $activitiesId = $model->getIdByField('iati_activities', 'account_id', $identity->account_id);
        
        //Create activity and its defaults
        $iatiIdentifier['activity_identifier'] = $data['identifier'];
        $iatiIdentifier['iati_identifier'] = $default['reporting_org_ref']."-".trim($data['identifier']);
        $activityId = $modelActivity->createActivity($activitiesId, $default , $iatiIdentifier);
        $this->activityId = $activityId;
        
        //Create Recipient Organisation( this is set to nepal)
        $recOrg = array();
        $recOrg['@code'] = 156;
        $recOrg['activity_id'] = $activityId;
        $model->insertRowsToTable('iati_recipient_country' , $recOrg);
    
        //Create title
        $title['text'] = $data['title'];
        $title['@xml_lang'] = $default['language'];
        $title['activity_id'] = $activityId;
        $model->insertRowsToTable('iati_title' , $title);
        
        //Create Description
        $description['@type'] = 1; //@todo check.
        $description['@xml_lang'] = $default['language'];
        $description['text'] = $data['description'];
        $description['activity_id'] = $activityId;
        $model->insertRowsToTable('iati_description' , $description);
        
        //Create funding org
        // Funding org data is received as a comma seperated value
        if($data['funding_org']){
            $fundingOrgs = explode(',' , $data['funding_org']);
            foreach($fundingOrgs as $fundingOrg){
                $funding = array();
                $funding['@role'] = 1;
                $funding['text'] = $fundingOrg;
                $funding['@xml_lang'] = $default['language'];
                $funding['activity_id'] = $activityId;
                $model->insertRowsToTable('iati_participating_org' , $funding);
            }
        }
        
        //Create Start date
        if($data['start_date']){
            $startDate['@iso_date'] = $data['start_date'];
            $startDate['@type'] = 3;
            $startDate['@xml_lang'] = $default['language'];
            $startDate['text'] = '';
            $startDate['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_activity_date' , $startDate);            
        }
        //Create End date
        if($data['end_date']){
            $endDate['@iso_date'] = $data['end_date'];
            $endDate['@type'] = 4;
            $endDate['@xml_lang'] = $default['language'];
            $endDate['text'] = '';
            $endDate['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_activity_date' , $endDate);            
        }
        
         //Create Sector
        if($this->hasValue($data['sector'])){
            foreach($data['sector'] as $sectorData){
                $sector['@code'] = $sectorData;
                $sector['@vocabulary'] = 3; // @todo check
                $sector['activity_id'] = $activityId;
                $model->insertRowsToTable('iati_sector' , $sector);
            }
        }
        
        //Create Status
        $this->saveStatus($data);
        
        //Create Document
        $this->addDocument($data['document_wrapper']['document']);

        //Create Location
        $this->addLocation($data['location_wrapper']['location']);
        
        //Create Budget
        $this->addBudget($data['budget_wrapper']['budget']);
        
        //Incomming Fund
        $this->addIncommingFund($data['incomming_fund_wrapper']['incommingFund']);
        
        //Expenditure
        $this->addExpenditure($data['expenditure_wrapper']['expenditure']);
        
        //Result
        $this->saveResult($data['result_wrapper']['result']);
        
        return $activityId;
        
    }
    
    /**
     * Function to fetch data used to populate form and display view page.
     */
    public function getDataForForm($activityId)
    {
        $data = array();
        // Get title
        $titleEle = new Iati_Aidstream_Element_Activity_Title();
        $titleValue = $titleEle->fetchData($activityId , true);
        $data['title_id'] = $titleValue[0]['id'];
        $data['title'] = $titleValue[0]['text'];
        
        // Get description
        $descriptionEle = new Iati_Aidstream_Element_Activity_Description();
        $descriptionValue = $descriptionEle->fetchData($activityId , true);
        $data['description_id'] = $descriptionValue[0]['id'];
        $data['description'] = $descriptionValue[0]['text'];
        
        // Get participating org
        $participatingOrgEle = new Iati_Aidstream_Element_Activity_ParticipatingOrg();
        $participatingOrgValue = $participatingOrgEle->fetchData($activityId , true);
        if(!empty($participatingOrgValue)){
            $count = 0;
            foreach($participatingOrgValue as $participatingOrg){
                $data['funding_org'][$count] = $participatingOrg['text'];
                $count++;
            }
        }
        
        // Get Activity date (start data and end date)
        $activityDateEle = new Iati_Aidstream_Element_Activity_ActivityDate();
        $activityDateValues = $activityDateEle->fetchData($activityId , true);
        foreach($activityDateValues as $activityDateValue){
            if($activityDateValue['@type'] == 3){
                $data['start_date_id'] = $activityDateValue['id'];
                $data['start_date'] = $activityDateValue['@iso_date'];       
            } else if($activityDateValue['@type'] == 4){
                $data['end_date_id'] = $activityDateValue['id'];
                $data['end_date'] = $activityDateValue['@iso_date'];                   
            }
        }
        
        // Get Document
        $documentEle = new Iati_Aidstream_Element_Activity_DocumentLink();
        $documentValue = $documentEle->fetchData($activityId , true);
        $count = 0;
        if(!empty($documentValue)){
            foreach($documentValue as $document){
                $data['document'][$count]['id'] = $document['id'];
                $data['document'][$count]['url'] = $document['@url'];
                
                // get document category
                $docCat = $document['Category'];
                if(!empty($docCat)){
                    $data['document'][$count]['category_id'] = $docCat[0]['id'];
                    $data['document'][$count]['category_code'] = $docCat[0]['@code'];
                }
                // get document title
                $docTitle = $document['Title'];
                if(!empty($docTitle)){
                    $data['document'][$count]['title_id'] = $docTitle[0]['id'];
                    $data['document'][$count]['title'] = $docTitle[0]['text'];
                }
                $count++;
            }
        }
        
        // Get location
        $locationEle = new Iati_Aidstream_Element_Activity_Location();
        $locationValues = $locationEle->fetchData($activityId , true);
        $count = 0;
        if(!empty($locationValues)){
            foreach($locationValues as $locationValue){
                $data['location'][$count]['location_id'] = $locationValue['id'];
                
                //get location name
                $locationName =  $locationValue['Name'];
                if(!empty($locationName)){
                    $data['location'][$count]['location_name_id'] = $locationName[0]['id'];
                    $data['location'][$count]['location_name'] = $locationName[0]['text'];
                }
                //get location description
                $locationDesc =  $locationValue['Description'];
                if(!empty($locationDesc)){
                    $data['location'][$count]['location_desc_id'] = $locationDesc[0]['id'];
                    $vdcs = preg_replace('/ -.*$/', '' , $locationDesc[0]['text']);
                    if($vdcs == VDCS_DEFAULT_VALUE){ $vdcs = '';}
                    $data['location'][$count]['location_vdcs'] = $vdcs;
                }

                //get location coordinates
                $locationCoords =  $locationValue['Coordinates'];
                if(!empty($locationCoords)){
                    $data['location'][$count]['location_coord_id'] = $locationCoords['id'];
                }

                //get location administrative
                $locationAdm =  $locationValue['Administrative'];
                if(!empty($locationAdm)){
                    $data['location'][$count]['location_adm_id'] = $locationAdm['id'];
                }
                $count++;
            }
        }

         // Get budget
        $budgetEle = new Iati_Aidstream_Element_Activity_Budget();
        $budgetValues = $budgetEle->fetchData($activityId , true);
        $count = 0;
        if(!empty($budgetValues)){
            foreach($budgetValues as $budget){
                $data['budget'][$count]['id'] = $budget['id'];
                //get budget value
                $budgetVal =  $budget['Value'];
                if(!empty($budgetVal)){
                    $data['budget'][$count]['value_id'] = $budgetVal['id'];
                    $data['budget'][$count]['currency'] = $budgetVal['@currency'];
                    $data['budget'][$count]['amount'] = $budgetVal['text'];
                    $data['budget'][$count]['signed_date'] = $budgetVal['@value_date'];
                }
                
                //get budget start date
                $budgetStart =  $budget['PeriodStart'];
                if(!empty($budgetStart)){
                    $data['budget'][$count]['start_id'] = $budgetStart['id'];
                    $data['budget'][$count]['start_date'] = $budgetStart['@iso_date'];
                }
                
                //get budget end date
                $budgetEnd =  $budget['PeriodEnd'];
                if(!empty($budgetEnd)){
                    $data['budget'][$count]['end_id'] = $budgetEnd['id'];
                    $data['budget'][$count]['end_date'] = $budgetEnd['@iso_date'];
                }
            $count++;
            }
        }
        
        // Get transaction
        $transactionEle = new Iati_Aidstream_Element_Activity_Transaction();
        $transactionValues = $transactionEle->fetchData($activityId , true);
        $expCount = 0;
        $incomCount = 0;
        $commCount = 0;
        if(!empty($transactionValues)){
            foreach($transactionValues as $transaction){
                //get transaction start date
                $transactionType =  $transaction['TransactionType'];
                if(!empty($transactionType)){
                    $code = $transactionType['@code'];
                    if($code == 4){
                        $type = 'expenditure';
                        $count = $expCount;
                        $expCount ++;
                    } elseif ($code == 5){
                        $type = 'incommingFund';
                        $count = $incomCount;
                        $incomCount++;
                    }
                }
                //get transaction value
                $transactionVal =  $transaction['TransactionValue'];
                if(!empty($transactionVal)){
                    $data[$type][$count]['id'] = $transaction['id'];
                    $data[$type][$count]['value_id'] = $transactionVal['id'];
                    $data[$type][$count]['currency'] = $transactionVal['@currency'];
                    $data[$type][$count]['start_date'] = $transactionVal['@value_date'];
                    $data[$type][$count]['amount'] = $transactionVal['text'];
                }
            }
        }
        
        // Get sector
        $sectorEle = new Iati_Aidstream_Element_Activity_Sector();
        $sectors = $sectorEle->fetchData($activityId , true);
        if(!empty($sectors)){
            $count = 0;
            foreach($sectors as $sector){
                $data['sector'][$count]['sector'] = $sector['@code'];
                $count++;
            }
        }
        
        //Get Status
        $statusEle = new Iati_Aidstream_Element_Activity_ActivityStatus();
        $status = $statusEle->fetchData($activityId , true);
        if(!empty($status)){
            $data['status_id'] = $status['id'];
            $data['status'] = $status['@code'];
        }
        
        //Get Result
        $count = 0;
        $resultEle = new Iati_Aidstream_Element_Activity_Result();
        $results = $resultEle->fetchData($activityId , true);

        foreach($results as $result){
            $data['result'][$count]['id'] = $result['id'];
            $data['result'][$count]['result_type']= $result['@type'];
            $data['result'][$count]['title_id'] = $result['Title'][0]['id'];
            $data['result'][$count]['title'] = $result['Title'][0]['text'];
            $data['result'][$count]['description_id'] = $result['Description'][0]['id'];
            $data['result'][$count]['description'] = $result['Description'][0]['text'];
            $data['result'][$count]['indicator_id'] = $result['Indicator'][0]['id'];
            $data['result'][$count]['indicator_title_id'] = $result['Indicator'][0]['Title'][0]['id'];
            $data['result'][$count]['indicator'] = $result['Indicator'][0]['Title'][0]['text'];
            $data['result'][$count]['period_id'] = $result['Indicator'][0]['Period'][0]['id'];
            $data['result'][$count]['actual_id'] = $result['Indicator'][0]['Period'][0]['Actual'][0]['id'];
            $data['result'][$count]['achievement'] = $result['Indicator'][0]['Period'][0]['Actual'][0]['@value'];
            $data['result'][$count]['period_end_id'] = $result['Indicator'][0]['Period'][0]['PeriodEnd'][0]['id'];
            $data['result'][$count]['end_date'] = $result['Indicator'][0]['Period'][0]['PeriodEnd'][0]['@iso_date'];
            $count++;
        }
        return $data;
    }
    
    /**
     * @todo break this function to smaller functions
     */
    public function updateActivity($data , $default)
    {
       // var_dump($data);exit;
        $model = $this->model;
        $activityId = $data['activity_id'];
        $this->activityId = $activityId;
        $this->defaults = $default;
        
        //Update Reporting Organisation
        $repOrg = $model->getRowsByFields('iati_reporting_org' , 'activity_id' , $activityId);
        $reportingOrg = array();
        $reportingOrg['id'] = $repOrg[0]['id'];
        $reportingOrg['@ref'] = $default['reporting_org_ref'];
        $reportingOrg['text'] = $default['reporting_org'];
        $reportingOrg['@type'] = $default['reporting_org_type'];
        $reportingOrg['@xml_lang'] = $default['reporting_org_lang'];
        $model->updateRowsToTable('iati_reporting_org' , $reportingOrg);

        //Update Activity Identifier
        if($data['identifier_id']){
            $iatiIdentifier['activity_identifier'] = $data['identifier'];
            $iatiIdentifier['text'] = $default['reporting_org_ref']."-".trim($data['identifier']);
            $iatiIdentifier['id'] = $data['identifier_id'];
            $model->updateRowsToTable('iati_identifier' , $iatiIdentifier);
        }
        
        
        
        //Update title
        if($data['title_id']){
            $title['text'] = $data['title'];
            $title['@xml_lang'] = $default['language'];
            $title['id'] = $data['title_id'];
            $model->updateRowsToTable('iati_title' , $title);
        } else {
            $title['text'] = $data['title'];
            $title['@xml_lang'] = $default['language'];
            $title['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_title' , $title);
        }

        //Update Description
        if($data['description_id']){
            $description['@xml_lang'] = $default['language'];
            $description['text'] = $data['description'];
            $description['id'] = $data['description_id'];
            $model->updateRowsToTable('iati_description' , $description);
        } else {
            $description['@type'] = 1; //@todo check.
            $description['@xml_lang'] = $default['language'];
            $description['text'] = $data['description'];
            $description['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_description' , $description);
        }

        //Update funding org
        
        // First fetch all funding orgs for the activity.
        $fundingOrgModel = new Simplified_Model_DbTable_FundingOrg();
        $fundingOrganisations = $fundingOrgModel->getFundingOrgsByActivityId($activityId);
        $fundingOrganisationCodes = array();
        if(!empty($fundingOrganisations)){
            foreach($fundingOrganisations as $fundingOrganisationValues){
                $fundingOrganisationCodes[$fundingOrganisationValues['id']] = $fundingOrganisationValues['text'];
            }
        }
        if($data['funding_org']){
            $fundingOrgs = explode(',' , $data['funding_org']);
            // Check already present Funding Orgs in the input data. unset existing data from input data and remove ids.
            foreach($fundingOrgs as $key=>$fundingOrgData){
                if(!empty($fundingOrganisationCodes)){
                    foreach($fundingOrganisationCodes as $id=>$code){
                        if($code == $fundingOrgData){
                            unset($fundingOrganisationCodes[$id]);
                            unset($fundingOrgs[$key]);
                        } 
                    }
                }
            }           
        }

        if($fundingOrgs){
            foreach($fundingOrgs as $fundingOrg){
                $funding = array();
                $funding['@role'] = 1;
                $funding['text'] = $fundingOrg;
                $funding['@xml_lang'] = $default['language'];
                $funding['activity_id'] = $activityId;
                $model->insertRowsToTable('iati_participating_org' , $funding);
            }
        }
        
        if(!empty($fundingOrganisationCodes)){
            $fundingOrgModel->deleteFundingOrgsByIds(array_keys($fundingOrganisationCodes));
        }

        //Update Start date
        if($data['start_date_id']){
            $startData = array();
            $startDate['@iso_date'] = $data['start_date'];
            $startDate['@xml_lang'] = $default['language'];
            $startDate['id'] = $data['start_date_id'];
            $model->updateRowsToTable('iati_activity_date' , $startDate);
        } else {
            $startData = array();
            $startDate['@iso_date'] = $data['start_date'];
            $startDate['@type'] = 3;
            $startDate['@xml_lang'] = $default['language'];
            $startDate['text'] = '';
            $startDate['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_activity_date' , $startDate); 
        }

        //Update End date
        if($data['end_date_id']){
            $endDate = array();
            $endDate['@iso_date'] = $data['end_date'];
            $endDate['@xml_lang'] = $default['language'];
            $endDate['text'] = '';
            $endDate['id'] = $data['end_date_id'];
            $model->updateRowsToTable('iati_activity_date' , $endDate);
        } else {
            $endDate = array();
            $endDate['@iso_date'] = $data['end_date'];
            $endDate['@type'] = 4;
            $endDate['@xml_lang'] = $default['language'];
            $endDate['text'] = '';
            $endDate['activity_id'] = $activityId;
            $model->insertRowsToTable('iati_activity_date' , $endDate); 
        }
        
        //Update Status
        $this->saveStatus($data);
        
        //Update Document
        $this->updateDocument($data['document_wrapper']['document']);

        //Update Location
        $this->updateLocation($data['location']);

        //Update Budget
        $this->updateBudget($data['budget_wrapper']['budget'] );
 
        //Update Incomming Fund
        $this->updateIncommingFund($data['incomming_fund_wrapper']['incommingFund']);
        
        //Update Expenditure
        $this->updateExpenditure($data['expenditure_wrapper']['expenditure']);
        
        //Update Sector
        
        // First fetch all sectors for the activity.
        $sectors = $model->listAll('iati_sector' , 'activity_id' , $activityId);
        $sectorIds = array();
        if(!empty($sectors)){
            foreach($sectors as $sectorValues){
                $sectorIds[$sectorValues['id']] = $sectorValues['@code'];
            }
        }
        if($this->hasValue($data['sector'])){          
            // Check already present sectors in the input data. unset existing data from input data and remove ids.
            foreach($data['sector'] as $key=>$sectorData){
                if(!empty($sectorIds)){
                    foreach($sectorIds as $id=>$code){
                        if($code == $sectorData){
                            unset($sectorIds[$id]);
                            unset($data['sector'][$key]);
                        } 
                    }
                }
            }
        }
        // Insert remaining input data to table if any
        if(!empty($data['sector'])){
            foreach($data['sector'] as $sectorData){
                    $sector = array();
                    $sector['@code'] = $sectorData;
                    $sector['@vocabulary'] = 3; // @todo check
                    $sector['activity_id'] = $activityId;
                    $model->insertRowsToTable('iati_sector' , $sector);
                }
        }
        
        // Remove existing sector data for the activity that is not entered as input if any.
        if(!empty($sectorIds)){
            $secModel = new Simplified_Model_DbTable_Sector();
            $secModel->deleteSectorsByIds(array_keys($sectorIds));
        }
        $this->saveResult($data['result_wrapper']['result']);
    }
    
    public function hasValue($data)
    {
        if($data){
            foreach($data as $key=>$value){
                if($key && ($key == 'remove' || $key == 'add')) continue;
                if($value) return true;
            }
        }
        return false;
    }
    
    public function getLocationAdms($district)
    {
        $ch = curl_init();
        $url = LOCATION_API.'np'."/divisions/3/".$district;
        curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        $response = curl_exec($ch);
        $out = array();
        // Check if any error occured
	if(!curl_errno($ch)){
            $data = json_decode($response);
            foreach($data->parents as $parents){
                if($parents->administrative_level == 1){
                    $out['adm1'] = $parents->division_name;
                } else if($parents->administrative_level == 2){
                    $out['adm2'] = $parents->division_name;
                }
            }
	}
        return $out;
    }
    
    public function addDocument($data)
    {
        foreach($data as $document){
            if($this->hasValue($document)){
                if($document['url']){
                    $docUrl['@url'] = $document['url'];
                    $docUrl['activity_id'] = $this->activityId;
                    $documentId = $this->model->insertRowsToTable('iati_document_link' , $docUrl);
                    
                    //Insert document link category
                    if($document['category_code']){
                        $docCat['@code'] = $document['category_code'];
                        $docCat['text'] = '';
                        $docCat['@xml_lang'] = $this->defaults['language']; 
                        $docCat['document_link_id'] = $documentId;
                        $this->model->insertRowsToTable('iati_document_link/category' , $docCat);
                    }
        
                     //Insert document link title
                    if($document['title']){
                        $docTitle['text'] = $document['title'];
                        $docTitle['@xml_lang'] = $this->defaults['language']; 
                        $docTitle['document_link_id'] = $documentId;
                        $this->model->insertRowsToTable('iati_document_link/title' , $docTitle);
                    }          
                }
            }
        }
    }
    
    public function addLocation($data)
    {
        //Create Location
        foreach($data as $locationData){
            if(!$locationData['location_name']) continue;
            // Insert location
            $locationId = $this->model->insertRowsToTable('iati_location' , array('activity_id' => $this->activityId));
            
            // Insert location type code (default to PPL)
            $locType = array();
            $locType['@code'] = 5;
            $locType['location_id'] = $locationId;
            $this->model->insertRowsToTable('iati_location/location_type' , $locType);
            
            // insert location name
            $locName = array();
            $locName['text'] = $locationData['location_name'];
            $locName['location_id'] = $locationId;
            $this->model->insertRowsToTable('iati_location/name' , $locName);
            
            // Insert Location Description
            $locDesc = array();
            if(!empty($locationData['location_vdcs'])){
                $text = implode(',' , $locationData['location_vdcs']);
                $text .= " - vdc/s of ".$locationData['location_name'];
            } else {
                $text = VDCS_DEFAULT_VALUE;
            }
            $locDesc['text'] = $text;
            $locDesc['location_id'] = $locationId;
            $this->model->insertRowsToTable('iati_location/description' , $locDesc);
            
            // Insert Location adm
            $adms = $this->getLocationAdms($locationData['location_name']);
            $locAdm = array();
            $locAdm['@adm1'] = $adms['adm1'];
            $locAdm['@adm2'] = $adms['adm2'];
            $locAdm['@country'] = 156; // Country code for nepal
            $locAdm['text'] = $adms['adm2']." , ".$adms['adm1'];
            $locAdm['location_id'] = $locationId;
            $this->model->insertRowsToTable('iati_location/administrative' , $locAdm);
            
            // Insert Location coords..
            $coords = $this->getCoordinates($locationData['location_name']);
            $locCoord = array();
            $locCoord['@latitude'] = $coords['lat'];
            $locCoord['@longitude'] = $coords['lng'];
            $locCoord['@precision'] = 5; // Country code for nepal
            $locCoord['location_id'] = $locationId;
            $this->model->insertRowsToTable('iati_location/coordinates' , $locCoord);            
        }
    }
    
    public function addBudget($data)
    {
         //Create Budget
        foreach($data as $budgetData){
            if($this->hasValue($budgetData)){
                $budget['@type'] = '';
                $budget['activity_id'] = $this->activityId;
                $budgetId = $this->model->insertRowsToTable('iati_budget' , $budget);
                
                //Insert Budget value
                if($budgetData['amount']){
                    $budValue['text'] = $budgetData['amount'];
                    $budValue['@value_date'] = $budgetData['signed_date'];
                    $budValue['@currency'] = $budgetData['currency'];
                    $budValue['budget_id'] = $budgetId;
                    $this->model->insertRowsToTable('iati_budget/value' , $budValue);
                }
                
                //Insert Budget Start
                if($budgetData['start_date']){
                    $budStart['@iso_date'] = $budgetData['start_date'];
                    $budStart['budget_id'] = $budgetId;
                    $this->model->insertRowsToTable('iati_budget/period_start' , $budStart);
                }
                
                //Insert Budget End
                if($budgetData['end_date']){
                    $budEnd['@iso_date'] = $budgetData['end_date'];
                    $budEnd['budget_id'] = $budgetId;
                    $this->model->insertRowsToTable('iati_budget/period_end' , $budEnd);
                }
            }
        }
    }
    
    public function addIncommingFund($data)
    {
        foreach($data as $incommingFund){
            if($this->hasValue($incommingFund)){
                $tran['activity_id'] = $this->activityId;
                $transactionId = $this->model->insertRowsToTable('iati_transaction' , $tran);
                //Insert transaction type
                $tranType['@code'] = 5;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                //Insert transaction value
                $tranValue['@currency'] = $incommingFund['currency'];
                $tranValue['text'] = $incommingFund['amount'];
                $tranValue['@value_date'] = $incommingFund['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
    }
    
    public function addExpenditure($data)
    {
        foreach($data as $expenditure){
            if($this->hasValue($expenditure)){
                $tran['activity_id'] = $this->activityId;
                $transactionId = $this->model->insertRowsToTable('iati_transaction' , $tran);
                //Insert transaction type
                $tranType['@code'] = 4;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                //Insert transaction value
                $tranValue['@currency'] = $expenditure['currency'];
                $tranValue['text'] = $expenditure['amount'];
                $tranValue['@value_date'] = $expenditure['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/value' , $tranValue);

            }
        }
    }
    
    public function updateDocument($data)
    {
        foreach($data as $documentData){
            if($documentData['id']){
                $docUrl = array();
                $docUrl['@url'] = $documentData['url'];
                $docUrl['id'] = $documentData['id'];
                $documentId = $this->model->updateRowsToTable('iati_document_link' , $docUrl);
    
                //update document link category
                if($documentData['category_id']){
                    $docCat = array();
                    $docCat['@code'] = $documentData['category_code'];
                    $docCat['id'] = $documentData['category_id'];
                    $this->model->updateRowsToTable('iati_document_link/category' , $docCat);
                }
    
                //update document link title
                if($documentData['title_id']){
                    $docTitle = array();
                    $docTitle['text'] = $documentData['title'];
                    $docTitle['id'] = $documentData['title_id'];
                    $this->model->updateRowsToTable('iati_document_link/title' , $docTitle);
                }
                
            } elseif($this->hasValue($documentData)){
                $docUrl = array();
                $docUrl['@url'] = $documentData['url'];
                $docUrl['activity_id'] = $this->activityId;
                $documentId = $this->model->insertRowsToTable('iati_document_link' , $docUrl);
                
                //Insert document link category
                $docCat = array();
                $docCat['@code'] = $documentData['category_code'];
                $docCat['text'] = '';
                $docCat['@xml_lang'] = $this->defaults['language']; 
                $docCat['document_link_id'] = $documentId;
                $this->model->insertRowsToTable('iati_document_link/category' , $docCat);
    
                //Insert document link title
                $docTitle = array();
                $docTitle['text'] = $documentData['title'];
                $docTitle['document_link_id'] = $documentId;
                $this->model->insertRowsToTable('iati_document_link/title' , $docTitle);
            }
        }
    }
    
    public function updateLocation($data)
    {
        foreach($data as $locationData){
            $locationId = $locationData['location_id'];
            if(!$locationId){
                if(!$locationData['location_name']) continue;
                // Insert location
                $locationId = $this->model->insertRowsToTable('iati_location' , array('activity_id' => $this->activityId));
                
                // Insert location type code (default to PPL)
                $locType = array();
                $locType['@code'] = 5;
                $locType['location_id'] = $locationId;
                $this->model->insertRowsToTable('iati_location/location_type' , $locType);
                
                // insert location name
                $locName = array();
                $locName['text'] = $locationData['location_name'];
                $locName['location_id'] = $locationId;
                $this->model->insertRowsToTable('iati_location/name' , $locName);
                
                // Insert Location Description
                $locDesc = array();
                if(!empty($locationData['location_vdcs'])){
                    $text = implode(',' , $locationData['location_vdcs']);
                    $text .= " - vdcs of ".$locationData['location_name'];
                } else {
                    $text = VDCS_DEFAULT_VALUE;
                }
                $locDesc['text'] = $text;
                $locDesc['location_id'] = $locationId;
                $this->model->insertRowsToTable('iati_location/description' , $locDesc);
                
                // Insert Location adm
                $adms = $this->getLocationAdms($locationData['location_name']);
                $locAdm = array();
                $locAdm['@adm1'] = $adms['adm1'];
                $locAdm['@adm2'] = $adms['adm2'];
                $locAdm['@country'] = 156; // Country code for nepal
                $locAdm['location_id'] = $locationId;
                $this->model->insertRowsToTable('iati_location/administrative' , $locAdm);
                
                // Insert Location coords..
                $coords = $this->getCoordinates($locationData['location_name']);
                $locCoord = array();
                $locCoord['@latitude'] = $coords['lat'];
                $locCoord['@longitude'] = $coords['lng'];
                $locCoord['@precision'] = 5; 
                $locCoord['location_id'] = $locationId;
                $this->model->insertRowsToTable('iati_location/coordinates' , $locCoord);
                
            } else {
                
                // update location name
                $locName = array();
                $locName['text'] = $locationData['location_name'];
                $locName['id'] = $locationData['location_name_id'];
                $this->model->updateRowsToTable('iati_location/name' , $locName);
                    
                // Update Location Description
                $locDesc = array();
                if(!empty($locationData['location_vdcs'])){
                    $text = implode(',' , $locationData['location_vdcs']);
                    $text .= " - vdc/s of ".$locationData['location_name'];
                } else {
                    $text = 'District';
                }
                $locDesc['text'] = $text;
                if($locationData['location_desc_id']){
                    $locDesc['id'] = $locationData['location_desc_id'];
                    $this->model->updateRowsToTable('iati_location/description' , $locDesc);
                } else {
                    $locDesc['location_id'] = $locationId;
                    $this->model->insertRowsToTable('iati_location/description' , $locDesc);
                }
                
                // Update Location adm
                $adms = $this->getLocationAdms($locationData['location_name']);
                $locAdm = array();
                $locAdm['@adm1'] = $adms['adm1'];
                $locAdm['@adm2'] = $adms['adm2'];
                if(!$locationData['location_adm_id']){
                    $locAdm['@country'] = 156; // Country code for nepal
                    $locAdm['location_id'] = $locationId;
                    $this->model->insertRowsToTable('iati_location/administrative' , $locAdm);
                } else {
                    $locAdm['text'] = $adms['adm2']." , ".$adms['adm1'];
                    $locAdm['id'] = $locationData['location_adm_id'];
                    $this->model->updateRowsToTable('iati_location/administrative' , $locAdm);
                }
                
                // Update Location coords..
                $coords = $this->getCoordinates($locationData['location_name']);
                $locCoord = array();
                $locCoord['@latitude'] = $coords['lat'];
                $locCoord['@longitude'] = $coords['lng'];
                if($locationData['location_coord_id']){
                    $locCoord['id'] = $locationData['location_coord_id'];
                    $this->model->updateRowsToTable('iati_location/coordinates' , $locCoord);
                } else {
                    $locCoord['@precision'] = 5; 
                    $locCoord['location_id'] = $locationId;
                    $this->model->insertRowsToTable('iati_location/coordinates' , $locCoord);
                }
            }
        }
    }
    
    public function updateIncommingFund($data)
    {
        foreach($data as $incommingFund){
            if($incommingFund['value_id']){
                //update transaction value
                $tranValue = array();
                $tranValue['@currency'] = $incommingFund['currency'];
                $tranValue['text'] = $incommingFund['amount'];
                $tranValue['@value_date'] = $incommingFund['start_date'];
                $tranValue['id'] = $incommingFund['value_id'];
                $this->model->updateRowsToTable('iati_transaction/value' , $tranValue);
            } elseif($this->hasValue($incommingFund)) {
                $tran = array();
                $tran['activity_id'] = $this->activityId;
                $transactionId = $this->model->insertRowsToTable('iati_transaction' , $tran);
                //update transaction type
                $tranType = array();
                $tranType['@code'] = 5;
                $tranType['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                //update transaction value
                $tranValue = array();
                $tranValue['@currency'] = $incommingFund['currency'];
                $tranValue['text'] = $incommingFund['amount'];
                $tranValue['@value_date'] = $incommingFund['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
    }
    
    public function updateBudget($data)
    {
       foreach($data as $budgetData){
            if($budgetData['id']){
                //update Budget value
                if($budgetData['value_id']){
                    $budValue = array();
                    $budValue['text'] = $budgetData['amount'];
                    $budValue['@value_date'] = $budgetData['signed_date'];
                    $budValue['@currency'] = $budgetData['currency'];
                    $budValue['id'] = $budgetData['value_id'];
                    $this->model->updateRowsToTable('iati_budget/value' , $budValue);
                }
                
                //update Budget Start
                if($budgetData['start_id']){
                    $budStart = array();
                    $budStart['@iso_date'] = $budgetData['start_date'];
                    $budStart['id'] = $budgetData['start_id'];
                    $this->model->updateRowsToTable('iati_budget/period_start' , $budStart);
                }
                
                //update Budget End
                if($budgetData['end_id']){
                    $budEnd = array();
                    $budEnd['@iso_date'] = $budgetData['end_date'];
                    $budEnd['id'] = $budgetData['end_id'];
                    $this->model->updateRowsToTable('iati_budget/period_end' , $budEnd);
                }
            } elseif($this->hasValue($budgetData)) {
                $budget = array();
                $budget['@type'] = '';
                $budget['activity_id'] = $this->activityId;
                $budgetId = $this->model->insertRowsToTable('iati_budget' , $budget);

                //Insert Budget value
                $budValue = array();
                $budValue['text'] = $budgetData['amount'];
                $budValue['@value_date'] = $budgetData['signed_date'];
                $budValue['@currency'] = $budgetData['currency'];
                $budValue['budget_id'] = $budgetId;
                $this->model->insertRowsToTable('iati_budget/value' , $budValue);
                
                //Insert Budget Start
                $budStart = array();
                $budStart['@iso_date'] = $budgetData['start_date'];
                $budStart['budget_id'] = $budgetId;
                $this->model->insertRowsToTable('iati_budget/period_start' , $budStart);
                
                //Insert Budget End
                $budEnd = array();
                $budEnd['@iso_date'] = $budgetData['end_date'];
                $budEnd['budget_id'] = $budgetId;
                $this->model->insertRowsToTable('iati_budget/period_end' , $budEnd);
            }
        } 
    }
    
    public function updateExpenditure($data)
    {
        foreach($data as $expenditure){
            if($expenditure['value_id']){
                $tranValue = array();
                $tranValue['@currency'] = $expenditure['currency'];
                $tranValue['text'] = $expenditure['amount'];
                $tranValue['@value_date'] = $expenditure['start_date'];
                $tranValue['id'] = $expenditure['value_id'];
                $this->model->updateRowsToTable('iati_transaction/value' , $tranValue);
            } elseif($this->hasValue($expenditure)) {
                $tran = array();
                $tran['activity_id'] = $this->activityId;
                $transactionId = $this->model->insertRowsToTable('iati_transaction' , $tran);
                //update transaction type
                $tranType = array();
                $tranType['@code'] = 4;
                $tranType['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);

                //update transaction value
                $tranValue = array();
                $tranValue['@currency'] = $expenditure['currency'];
                $tranValue['text'] = $expenditure['amount'];
                $tranValue['@value_date'] = $expenditure['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $this->model->insertRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
    }
    
    public function saveResult($data)
    {
        $resultEle = new Iati_Aidstream_Element_Activity_Result();
        if(!$resultEle->hasData($data)) return;
        $resultData = array();
        $count = 0;
        foreach($data as $result){            
            $resultData[$count]['id'] = $result['id'];
            $resultData[$count]['type'] = $result['result_type'];

            $resultData[$count]['Title'][0]['id'] = $result['title_id'];
            $resultData[$count]['Title'][0]['text'] = $result['title'];
            
            $resultData[$count]['Description'][0]['id'] = $result['description_id'];
            $resultData[$count]['Description'][0]['text'] = $result['description'];
            
            $resultData[$count]['Indicator'][0]['id'] = $result['indicator_id'];
            $resultData[$count]['Indicator'][0]['measure'] = 1;
            $resultData[$count]['Indicator'][0]['ascending'] = 'True';
            
            $resultData[$count]['Indicator'][0]['Title'][0]['id'] = $result['indicator_title_id'];
            $resultData[$count]['Indicator'][0]['Title'][0]['text'] = $result['indicator'];
            
            $resultData[$count]['Indicator'][0]['Period'][0]['id'] = $result['period_id'];
            
            $resultData[$count]['Indicator'][0]['Period'][0]['Actual'][0]['id'] = $result['actual_id'];
            $resultData[$count]['Indicator'][0]['Period'][0]['Actual'][0]['value'] = $result ['achievement'];
            
            $resultData[$count]['Indicator'][0]['Period'][0]['PeriodEnd'][0]['id']  = $result['period_end_id'];
            $resultData[$count]['Indicator'][0]['Period'][0]['PeriodEnd'][0]['iso_date'] = $result['end_date'];
            
            $count++;
        }

        $resultEle->save($resultData , $this->activityId);
    }
    
    public function saveStatus($data)
    {
        $statusEle = new Iati_Aidstream_Element_Activity_ActivityStatus();
        $status = array();
        $status['id'] = $data['status_id'];
        $status['code'] = $data['status'];
        $statusEle->save($status , $this->activityId);
    }
    
    public function getCoordinates($district)
    {
        $ch = curl_init();
        $url = LOCATION_API.'np'."/latlong/".$district;
        curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
        $response = curl_exec($ch);
        $out = array();
        if(!curl_errno($ch)){
            $data = json_decode($response);
            $out['lat'] = $data->lat;
            $out['lng'] = $data->lng;
        }
        return $out;
    }
}
