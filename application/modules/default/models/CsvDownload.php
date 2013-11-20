<?php
/**
 * Handles all csv downloads
 */
class Model_CsvDownload
{   
     /**
     * Function to download Array data to csv file.  *
     *
     * @param Array $data   Array data to download in csv.
     * @param Array $options        Options for download. Options include filename,seperator,enclosure,return data.
     * eg. array('filename'=>'myfile' , 'enclosure' => '"' , 'seperator' => ';' , 'return' => true)
     */
     public static function downloadArrayToCsv($data , $options = null)
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
                          $csvOutput .= $enclosure.$value.$enclosure.$seperator;
                  }
                  $csvOutput .= $lineBreak;
          }
      
          // return data if return option is set
          if(isset($options['return']) && $options['return'] == true){
                  return $csvOutput;
          }
      
          header( 'Content-Type: text/csv' );
          header( 'Content-Disposition: attachment;filename='.$filename.'.csv');
          echo $csvOutput;
          exit;
     }
     
     public function downloadCsv($type)
     {
          switch (strtolower($type)){
               case 'budget' :
                   return $this->downloadUserBudget();
                   break;
               case 'transaction' :
                   return $this->downloadUserTransaction();
                   break;
               case 'simple' :
                    return $this->downloadSimpleFormat();
                    break;
               case 'complete' :
                    return $this->downloadCompleteData();
                    break;
               default:
                   return "Invalid type for download";
                   break;
          }
     }
     
     /**
      * Takes the array keys from the data and prepends the array of keys.
      */
     public function addHeaderFromKeys($data)
     {
         if(empty($data)) return;
         
         $keys = array_keys($data[0]);
         $dataWithHeader = array_unshift($data , $keys);
         return $data;
     }
     
     /**
      * Fetch all activity ids from account id. User activitycollection model
      */
     public function getActivityIds($accountId)
     {
          $ids = array();
          $actModel = new Model_ActivityCollection();
          
          $activityIds = $actModel->getActivityIdsByAccount($accountId);
          if(!empty($activityIds)){
               foreach($activityIds as $id){
                   $ids[] = $id['id'];
               }
          } 
          return $ids;
     }
     
     /**
      * Fetches data for all the activities id provided for the element.
      *
      * @param Array $activitiesId array of activity ids.
      * @param String $elementClass Fullname of the element. eg. Activity_Transaction
      *
      * @return Array Array of element data for all activities
      */
     public function getElementData($activitiesId , $elementClass)
     {
          if(empty($activitiesId)) return;
          
          $eleData = array();
          $className = "Iati_Aidstream_Element_".$elementClass;
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
     
     /**
     * Function to recursively convert fetched data to flat csv form
     *
     * @param Object $element Iati_Aidstream_Element object.
     * @param Array $elementData Array of elementData. Data fetched by element's fetchData function.
     */
     public function prepareCsvDataForElement($element , $elementData , $useParentAsKey = true)
     {
          if($element->getIsMultiple()){
               // For element that can be multiple prepare csv using data if present else generate csv fields.
               if($element->hasData($elementData)){
                    $csvData = array();
                    foreach($elementData as $data){
                         $individualData = $this->prepareCsvDataForSingle($element , $data , $useParentAsKey);
                         $temp[] = $individualData;
                    }
                    foreach($temp as $individualData){
                         foreach($individualData as $key=>$value){
                              $csvData[$key] .= ";$value";
                         }
                    }
                    $csvData = preg_replace('/^;/' , '' , $csvData);
               } else {
                    $csvData = $this->prepareCsvDataForSingle($element , $elementData , $useParentAsKey);
               }
          } else {
               $csvData = $this->prepareCsvDataForSingle($element , $elementData , $useParentAsKey);
          }
          return $csvData;
     }
     /**
      * Function to prepare csv for single element at a time
      */
     public function prepareCsvDataForSingle($element , $elementData , $useParentAsKey)
     {
          $eleData = $element->getElementsIatiData($elementData);          
          $csvData = array();
          foreach($eleData as $attrib => $value){
               $attribName = preg_replace('/@/' , '' , $attrib);
               $attribValue = '';
               if($value){
                   $attribValue = Iati_Core_Codelist::getCodeByAttrib($element->getClassName() , $attribName , $value);
               }
               if($useParentAsKey){
                   $key = $element->getFullName() ."-" . $attribName;
               } else {
                   $key = $element->getClassName() . "-" . $attribName;
               }
               $csvData[$key] = $attribValue;
          }
          $childElements = $element->getChildElements();
          if(!empty($childElements)){
               foreach($childElements as $childElementClass){
                    $childElementName = get_class($element)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childData = $elementData[$childElement->getClassName()];
                    $childCsvData = $this->prepareCsvDataForElement(
                                                                    $childElement ,
                                                                    $childData,
                                                                    $useParentAsKey
                                                                    );
                    $csvData = array_merge($csvData ,  $childCsvData);
               }
          }
          
          return $csvData;
     }
     
     /**
      * Function that handles the download steps for budget
      */
     public function downloadUserBudget($accountId = '')
     {
          if(!$accountId){
               $identity = Zend_Auth::getInstance()->getIdentity();
               $accountId = $identity->account_id;
          }
          $activitiesId = $this->getActivityIds($accountId);
         
         
          $budgetData = $this->getElementData($activitiesId , 'Activity_Budget');
          $csvData = $this->prepareBudgetCsvData($budgetData);
          if(!$csvData)  return;

          $csvDataWithHeader = $this->addHeaderFromKeys($csvData);
          self::downloadArrayToCsv($csvDataWithHeader , array('filename' => 'budget'));
     }
     
     public function prepareBudgetCsvData($budgetData)
     {
          $csvData = array();
          if(empty($budgetData)) return;
          
          $element = new Iati_Aidstream_Element_Activity_Budget();
          $element->setIsMultiple(false);
          $count = 0;
          foreach($budgetData as $budgetElementData)
          {
               $budget = $this->prepareCsvDataForElement($element , $budgetElementData);

               $csvData[$count]['Budget_Type'] = $budget['Activity_Budget-type'];
               $csvData[$count]['Period_Start_Date'] = $budget['Activity_Budget_PeriodStart-iso_date'];
               $csvData[$count]['Peroid_End_Date'] = $budget['Activity_Budget_PeriodEnd-iso_date'];
               $csvData[$count]['Value_Amount'] = $budget['Activity_Budget_Value-text'];
               $csvData[$count]['Currency'] = $budget['Activity_Budget_Value-currency'];
               $csvData[$count]['Value_Date'] = $budget['Activity_Budget_Value-value_date'];
               
               $count++;
          }
          
          return $csvData;
     }
     
     /**
      * Function that handles the download steps for transaction
     */
     public function downloadUserTransaction($accountId = '')
     {       
          if(!$accountId){
               $identity = Zend_Auth::getInstance()->getIdentity();
               $accountId = $identity->account_id;
          }
          $activitiesId = $this->getActivityIds($accountId);
                  
          $transactionData = $this->getElementData($activitiesId , 'Activity_Transaction');
          $csvData = $this->prepareTransactionCsvData($transactionData);
          if(!$csvData)  return;

          $csvDataWithHeader = $this->addHeaderFromKeys($csvData);
          self::downloadArrayToCsv($csvDataWithHeader , array('filename' => 'transaction'));
     }
     
     public function prepareTransactionCsvData($transactionData)
     {
          if(empty($transactionData)) return;
          $csvData = array();
          $identity = Zend_Auth::getInstance()->getIdentity();
          $accountId = $identity->account_id;
          
          $model = new Model_DefaultFieldValues();
          $defaults = $model->getByOrganisationId($accountId);
          $currency = ($defaults['currency']) ? Iati_Core_Codelist::getCodeByAttrib(
                                                                                    'Transaction' ,
                                                                                    'currency' ,
                                                                                    $defaults['currency']
                                                                                    ):'';
          $element = new Iati_Aidstream_Element_Activity_Transaction();
          $element->setIsMultiple(false);
          
          $identifierText = array();
          $titleText = array();
          
          foreach($transactionData as $transactionElementData){
               $activityId = $transactionElementData['activity_id'];
               if(!$identifierText[$activityId]){ // fetch identifier for activity Id
                   $identifierEle = new Iati_Aidstream_Element_Activity_IatiIdentifier();
                   $identifierData = $identifierEle->fetchData($activityId , true);
                   $identifierText[$activityId] = $identifierData['text'];
               }
               
               if(!$titleText[$activityId]){// fetch title for activity Id
                   $titleEle = new Iati_Aidstream_Element_Activity_Title();
                   $titleData = $titleEle->fetchData($activityId , true);
                   $titleText[$activityId] = $titleData[0]['text'];
               }
               
               $data = $this->prepareCsvDataForElement($element , $transactionElementData , false);
               
               $extras = array(
                                   'Activity_Identifier' => $identifierText[$activityId] ,
                                   'Activity_Title' => $titleText[$activityId],
                                   'Default_currency' => $currency
                              );
               $data = array_merge($extras , $data);// Add identifier and title to csv data
               
               $csvData[] = $data;
          }
          return $csvData;
     }
     
     /**
      * Function that handles the download steps for complete activity data
     */
     public function downloadCompleteData($accountId)
     {
          if(!$accountId){
               $identity = Zend_Auth::getInstance()->getIdentity();
               $accountId = $identity->account_id;
          }
          $activitiesId = $this->getActivityIds($accountId);
          $activitiesData = $this->getElementData($activitiesId , 'Activity');
          $csvData = $this->prepareDataForCompleteDownload($activitiesData);
          if(!$csvData)  return;

          $csvDataWithHeader = $this->addHeaderFromKeys($csvData);
          self::downloadArrayToCsv($csvDataWithHeader , array('filename' => 'complete'));
     }
     
     public function prepareDataForCompleteDownload($activitiesData)
     {
          $element = new Iati_Aidstream_Element_Activity();
          $element->setIsMultiple(false);
          
          $csvData = array();
          foreach($activitiesData as $activityData){
               $data = $this->prepareCsvDataForElement($element , $activityData['Activity']);
               $csvData[] = $data;
          }
          return $csvData;
     }
     
     /**
      * Function that handles download steps for simple format.
      */
     public function downloadSimpleFormat($accountId)
     {
          if(!$accountId){
               $identity = Zend_Auth::getInstance()->getIdentity();
               $accountId = $identity->account_id;
          }
          $activitiesId = $this->getActivityIds($accountId);
          $csvData = $this->prepareDataForSimpleFormat($activitiesId);
          if(!$csvData)  return;
          
          $csvDataWithHeader = $this->addHeaderFromKeys($csvData);
          self::downloadArrayToCsv($csvDataWithHeader , array('filename' => 'simple'));
     }

     public function prepareDataForSimpleFormat($activitiesId)
     {
          if(empty($activitiesId)) return;
          $csvData = array();
          foreach($activitiesId as $activityId)
          {
               $actModel = new Model_Activity();
               $actInfo = $actModel->getActivityInfo($activityId);
               $act['default-language'] = $actInfo['@xml_lang'];
               $act['default-currency'] = $actInfo['@default_currency'];
               $act['last-updated-datetime'] = $actInfo['@last_updated_datetime'];
               
               $reportOrg = $this->prepareReportingOrgSimpleFormat($activityId);
               
               $act = array_merge($act , $reportOrg);
               $act['iati-identifier'] = $actInfo['iati_identifier'];
               $act['title'] = $actInfo['iati_title'];
               
               $desc = $this->prepareDescriptionSimpleFormat($activityId);
               $status = $this->prepareActivityStatusSimpleFormat($activityId);
               $date = $this->prepareActivityDateSimpleFormat($activityId);
               $transaction = $this->prepareTransactionSimpleFormat($activityId);
               $participatingOrg = $this->prepareParticipatingOrgSimpleFormat($activityId);
               $recptCountry = $this->prepareRecipientCountrySimpleFormat($activityId);
               $recptRegion = $this->prepareRecipientRegionSimpleFormat($activityId);
               $sector = $this->prepareSectorSimpleFormat($activityId);
               
               $activityData = array_merge($act, $desc, $status , $date, $transaction, $participatingOrg, $recptCountry, $recptRegion, $sector);
               $csvData[] = $activityData;
          }
          return $csvData;
     }
     
     protected function prepareReportingOrgSimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_ReportingOrg();
          $data = $element->fetchData($activityId , true);
          $returnData['reporting-organisation'] = $data['text'];
          $returnData['reporting-organisation-ref'] = $data['@ref'];
          $returnData['reporting-org-type'] = ($data['@type'])?Iati_Core_Codelist::getCodeByAttrib('ReportingOrg' , '@type' , $data['@type']):'';
          return $returnData;
     }
     
     protected function prepareDescriptionSimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_Description();
          $data = $element->fetchData($activityId , true);
          $csvData = $this->prepareCsvDataForElement($element , $data , false);
          $returnData['description'] = $csvData['Description-text'];
          return $returnData;
     }
     
     protected function prepareActivityStatusSimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_ActivityStatus();
          $data = $element->fetchData($activityId , true);
          $csvData = $this->prepareCsvDataForElement($element , $data , false);
          $returnData['activity-status'] = $csvData['ActivityStatus-code'];
          return $returnData;
     }
     
     protected function prepareActivityDateSimpleFormat($activityId)
     {
          $returnData = array(
                              'start-planned' => '',
                              'start-actual' => '',
                              'end-planned' => '',
                              'end-actual' => ''
                              );
          $element = new Iati_Aidstream_Element_Activity_ActivityDate();
          $data = $element->fetchData($activityId , true);
          if($element->hasData($data)){
               foreach($data as $elementData){
                    $date = ";".$elementData['@iso_date'];
                    switch($elementData['@type']){
                         case 1:
                              $returnData['start-planned'] .= $date;
                              break;
                         case 2:
                              $returnData['end-planned'] .= $date;
                              break;
                         case 3:
                              $returnData['start-actual'] .= $date;
                              break;
                         case 4:
                              $returnData['end-actual'] .= $date;
                              break;
                    }
               }
               $returnData = preg_replace('/^;/' , '' , $returnData);
          }
          return $returnData;
     }
     
     protected function prepareTransactionSimpleFormat($activityId)
     {
          $returnData = array('total-commitments' => 0, 'total-disbursements' => 0 ,
                              'total-expenditure' => 0, 'total-incoming-funds' => 0);
          
          $element = new Iati_Aidstream_Element_Activity_Transaction();
          $data = $element->fetchData($activityId , true);
          if($element->hasData($data)){
               foreach($data as $transactionData){
                    $value = $transactionData['TransactionValue']['text'];
                    switch ($transactionData['TransactionType']['@code']){
                         case 1:
                              $returnData['total-commitments'] += $value;
                              break;
                         case 2:
                              $returnData['total-disbursements'] += $value;
                              break;
                         case 4:
                              $returnData['total-expenditure'] += $value;
                              break;
                         case 5:
                              $returnData['total-incoming-funds'] += $value;
                              break;
                         default:
                              break;
                    }
               }
          }
          return $returnData;
     }
     
     protected function prepareParticipatingOrgSimpleFormat($activityId)
     {
          $returnData = array(
               'funding_organisations' => '',
               'extending_organisations' => '',
               'accountable_organisations' => '',
               'implementing_organisations' => ''
          );
          $element = new Iati_Aidstream_Element_Activity_ParticipatingOrg();
          $data = $element->fetchData($activityId , true);
          if($element->hasData($data)){
               foreach($data as $partOrg){
                    $value = ";".$partOrg['text'];
                    switch ($partOrg['@role']){
                         case 1:
                              $returnData['funding_organisations'] .= $value;
                              break;
                         case 2:
                              $returnData['extending_organisations'] .= $value;
                              break;
                         case 3:
                              $returnData['accountable_organisations'] .= $value;
                              break;
                         case 4:
                              $returnData['implementing_organisations'] .= $value;
                              break;
                         default:
                              break;
                    }
               }
               preg_replace('/^;/' , '' , $returnData);
          }
          return $returnData;
     }
     
     protected function prepareRecipientCountrySimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_RecipientCountry();
          $data = $element->fetchData($activityId , true);
          $csvData = $this->prepareCsvDataForElement($element , $data , false);

          $returnData['recipient-country'] = $csvData['RecipientCountry-text'];
          $returnData['recipient-country-codes'] = $csvData['RecipientCountry-code'];
          $returnData['recipient-country-percentages'] = $csvData['RecipientCountry-percentage'];
          return $returnData;
     }
     
     protected function prepareRecipientRegionSimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_RecipientRegion();
          $data = $element->fetchData($activityId , true);
          $csvData = $this->prepareCsvDataForElement($element , $data , false);

          $returnData['recipient-region'] = $csvData['RecipientRegion-text'];
          $returnData['recipient-region-codes'] = $csvData['RecipientRegion-code'];
          $returnData['recipient-region-percentages'] = $csvData['RecipientRegion-percentage'];
          return $returnData;
     }
     
     protected function prepareSectorSimpleFormat($activityId)
     {
          $returnData = array();
          $element = new Iati_Aidstream_Element_Activity_Sector();
          $data = $element->fetchData($activityId , true);
          $csvData = $this->prepareCsvDataForElement($element , $data , false);

          $returnData['sector-text'] = $csvData['Sector-text'];
          $returnData['sector-vocabularies'] = $csvData['Sector-vocabulary'];
          $returnData['sector-codes'] = $csvData['Sector-code'];
          $returnData['sector-percentages'] = $csvData['Sector-percentage'];
          return $returnData;
     }
    
}