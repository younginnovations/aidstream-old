<?php
class Simplified_Model_Simplified
{
    
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
     */
    public static function addActivity($data , $default)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        //var_dump($data);exit;
        $model = new Model_Wep();
        $modelActivity =  new Model_Activity();
        //Create activity and its defaults
        $iatiIdentifier['activity_identifier'] = $data['identifier'];
        $iatiIdentifier['iati_identifier'] = trim($data['identifier'])."_".$default['reporting_org_ref'];
        $activityId = $modelActivity->createActivity($identity->account_id , $default , $iatiIdentifier);
    
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
        $funding['@role'] = 1;
        $funding['text'] = $data['funding_org'];
        $funding['activity_id'] = $activityId;
        $model->insertRowsToTable('iati_participating_org' , $funding);
        
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
        //Create Document
        if($data['document_url']){
            $docUrl['@url'] = $data['document_url'];
            $docUrl['activity_id'] = $activityId;
            $documentId = $model->insertRowsToTable('iati_document_link' , $docUrl);

            //Insert document link category
            $docCat['@code'] = $data['document_category_code'];
            $docCat['text'] = '';
            $docCat['@xml_lang'] = $default['language']; 
            $docCat['document_link_id'] = $documentId;
            $model->insertRowsToTable('iati_document_link/category' , $docCat);

             //Insert document link title
            $docTitle['text'] = $data['document_title'];
            $docTitle['@xml_lang'] = $default['language']; 
            $docTitle['document_link_id'] = $documentId;
            $model->insertRowsToTable('iati_document_link/title' , $docTitle);
            
        }
        
        //Create Location
        if($data['location_name']){
            $loc['activity_id'] = $activityId;
            $locId = $model->insertRowsToTable('iati_location' , $loc);
            
            //Insert Location Name
            $locName['@xml_lang'] = $default['language'];
            $locName['text'] = $data['location_name'];
            $locName['location_id'] = $locId;
            $model->insertRowsToTable('iati_location/name' , $locName);
        }
        
        //Create Budget
        foreach($data['budget'] as $budgetData){
            if($budgetData['amount']){
                $budget['@type'] = '';
                $budget['activity_id'] = $activityId;
                $budgetId = $model->insertRowsToTable('iati_budget' , $budget);
                
                //Insert Budget value
                $budValue['text'] = $budgetData['amount'];
                $budValue['@value_date'] = $budgetData['signed_date'];
                $budValue['@currency'] = $budgetData['currency'];
                $budValue['budget_id'] = $budgetId;
                $model->insertRowsToTable('iati_budget/value' , $budValue);
                
                //Insert Budget Start
                $budStart['@iso_date'] = $budgetData['start_date'];
                $budStart['budget_id'] = $budgetId;
                $model->insertRowsToTable('iati_budget/period_start' , $budStart);
                
                //Insert Budget End
                $budEnd['@iso_date'] = $budgetData['end_date'];
                $budEnd['budget_id'] = $budgetId;
                $model->insertRowsToTable('iati_budget/period_end' , $budEnd);            
            }
        }
        
        //Create Transaction
        // Commitment
        foreach($data['commitment'] as $commitment){
            if($commitment['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                //Insert transaction type
                $tranType['@code'] = 1;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                 //Insert transaction value
                $tranValue['@currency'] = $commitment['currency'];
                $tranValue['text'] = $commitment['amount'];
                $tranValue['@value_date'] = $commitment['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
        //Incomming Fund
        foreach($data['incommingFund'] as $incommingFund){
            if($incommingFund['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                //Insert transaction type
                $tranType['@code'] = 5;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                //Insert transaction value
                $tranValue['@currency'] = $incommingFund['currency'];
                $tranValue['text'] = $incommingFund['amount'];
                $tranValue['@value_date'] = $incommingFund['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
        // Expenditure
        foreach($data['expenditure'] as $expenditure){
            if($expenditure['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                //Insert transaction type
                $tranType['@code'] = 4;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                //Insert transaction value
                $tranValue['@currency'] = $expenditure['currency'];
                $tranValue['text'] = $expenditure['amount'];
                $tranValue['@value_date'] = $expenditure['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->insertRowsToTable('iati_transaction/value' , $tranValue);

            }
        }
        
        //Create Sector
        $sector['@code'] = $data['sector'];
        $sector['@vocabulary'] = 3; // @todo check
        $sector['activity_id'] = $activityId;
        $model->insertRowsToTable('iati_sector' , $sector);
        
    }
    
    /**
     * Function to convert Rowset data to data used to populate form
     */
    public function getDataForForm($activity)
    {
        $data = array();
        // Get title
        $titleObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_TITLE);
        $titleValue = $titleObj[0]->getAttribs();
        $data['title_id'] = $titleValue['id'];
        $data['title'] = $titleValue['text'];
        
        // Get description
        $descriptionObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_DESCRIPTION);
        $descriptionValue = $descriptionObj[0]->getAttribs();
        $data['description_id'] = $descriptionValue['id'];
        $data['description'] = $descriptionValue['text'];
        
        // Get participating org
        $participatingOrgObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_PARTICIPATING_ORG);
        $participatingOrgValue = $participatingOrgObj[0]->getAttribs();
        $data['funding_org_id'] = $participatingOrgValue['id'];
        $data['funding_org'] = $participatingOrgValue['text'];
        
        // Get Activity date (start data and end date)
        $activityDateObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_ACTIVITY_DATE);
        foreach($activityDateObj as $activityDate){
            $activityDateValue = $activityDate->getAttribs();
            if($activityDateValue['@type'] == 3){
                $data['start_date_id'] = $activityDateValue['id'];
                $data['start_date'] = $activityDateValue['@iso_date'];       
            } else if($activityDateValue['@type'] == 4){
                $data['end_date_id'] = $activityDateValue['id'];
                $data['end_date'] = $activityDateValue['@iso_date'];                   
            }
        }
        
        // Get Document 
        $docObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_DOCUMENT_LINK);
        $data['document_id'] = $docObj[0]->getAttrib('id');
        $data['document_url'] = $docObj[0]->getAttrib('@url');
        // get document category
        $docCat = $docObj[0]->getElementsByType('Category');
        if($docCat){
            $data['document_category_id'] = $docCat[0]->getAttrib('id');
            $data['document_category_code'] = $docCat[0]->getAttrib('@code');
        }
        // get document title
        $docTitle = $docObj[0]->getElementsByType('Title');
        if($docTitle){
            $data['document_title_id'] = $docTitle[0]->getAttrib('id');
            $data['document_title'] = $docTitle[0]->getAttrib('text');
        }
        
        // Get location
        $locationObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_LOCATION);
        $location = $locationObj[0]->getAttribs();
        if($location['id']){
            //get location name
            $locationName =  $locationObj[0]->getElementsByType('Name');
            $locationValue = $locationName[0]->getAttribs();
            $data['location_id'] = $locationValue['id'];
            $data['location_name'] = $locationValue['text'];
        }
        
         // Get budget
        $budgetObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_BUDGET);
        $budget = $budgetObj[0]->getAttribs();
        $count = 0;
        if($budget['id']){
            foreach($budgetObj as $budget){
                //get budget value
                $budgetVal =  $budget->getElementsByType('Value');
                if(!empty($budgetVal)){
                    $data['budget'][$count]['value_id'] = $budgetVal[0]->getAttrib('id');
                    $data['budget'][$count]['currency'] = $budgetVal[0]->getAttrib('@currency');
                    $data['budget'][$count]['amount'] = $budgetVal[0]->getAttrib('text');
                    $data['budget'][$count]['signed_date'] = $budgetVal[0]->getAttrib('@value_date');
                }
                
                //get budget start date
                $budgetStart =  $budget->getElementsByType('PeriodStart');
                if(!empty($budgetStart)){
                    $data['budget'][$count]['start_id'] = $budgetStart[0]->getAttrib('id');
                    $data['budget'][$count]['start_date'] = $budgetStart[0]->getAttrib('@iso_date');
                }
                
                //get budget end date
                $budgetEnd =  $budget->getElementsByType('PeriodEnd');
                if(!empty($budgetEnd)){
                    $data['budget'][$count]['end_id'] = $budgetEnd[0]->getAttrib('id');
                    $data['budget'][$count]['end_date'] = $budgetEnd[0]->getAttrib('@iso_date');
                }
            $count++;
            }
        }
        
        // Get transaction
        $transactionObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_TRANSACTION);
        $transaction = $transactionObj[0]->getAttribs();
        $expCount = 0;
        $incomCount = 0;
        $commCount = 0;
        if(!empty($transaction)){
            foreach($transactionObj as $transaction){
                //get transaction start date
                $transactionType =  $transaction->getElementsByType('TransactionType');
                if(!empty($transactionType)){
                    $code = $transactionType[0]->getAttrib('@code');
                    if($code == 4){
                        $type = 'expenditure';
                        $count = $expCount;
                        $expCount ++;
                    } elseif ($code == 5){
                        $type = 'incommingFund';
                        $count = $incomCount;
                        $incomCount++;
                    } elseif($code == 1) {
                        $type = 'commitment';
                        $count = $commCount;
                        $commCount++;
                    }
                }
                //get transaction value
                $transactionVal =  $transaction->getElementsByType('Value');
                if(!empty($transactionVal)){
                    $data[$type][$count]['id'] = $transactionVal[0]->getAttrib('id');
                    $data[$type][$count]['currency'] = $transactionVal[0]->getAttrib('@currency');
                    $data[$type][$count]['start_date'] = $transactionVal[0]->getAttrib('@value_date');
                    $data[$type][$count]['amount'] = $transactionVal[0]->getAttrib('text');
                }
            }
        }
        
        // Get sector
        $sectorObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_SECTOR);
        $sector = $sectorObj[0]->getAttribs();
        $data['sector_id'] = $sector['id'];
        $data['sector'] = $sector['@code'];
        return $data;
    }
    
    public function updateActivity($data)
    {
        //Update title
        $title['text'] = $data['title'];
        $title['@xml_lang'] = $default['language'];
        $title['activity_id'] = $activityId;
        $model->updateRowsToTable('iati_title' , $title);
        
        //Update Description
        $description['@type'] = 1; //@todo check.
        $description['@xml_lang'] = $default['language'];
        $description['text'] = $data['description'];
        $description['activity_id'] = $activityId;
        $model->updateRowsToTable('iati_description' , $description);
        
        //Update funding org
        $funding['@role'] = 1;
        $funding['text'] = $data['funding_org'];
        $funding['activity_id'] = $activityId;
        $model->updateRowsToTable('iati_participating_org' , $funding);
        
        //Update Start date
        if($data['start_date']){
            $startDate['@iso_date'] = $data['start_date'];
            $startDate['@type'] = 3;
            $startDate['@xml_lang'] = $default['language'];
            $startDate['text'] = '';
            $startDate['activity_id'] = $activityId;
            $model->updateRowsToTable('iati_activity_date' , $startDate);            
        }
        //Update End date
        if($data['end_date']){
            $endDate['@iso_date'] = $data['end_date'];
            $endDate['@type'] = 4;
            $endDate['@xml_lang'] = $default['language'];
            $endDate['text'] = '';
            $endDate['activity_id'] = $activityId;
            $model->updateRowsToTable('iati_activity_date' , $endDate);            
        }
        //Update Document
        if($data['document_url']){
            $docUrl['@url'] = $data['document_url'];
            $docUrl['activity_id'] = $activityId;
            $documentId = $model->updateRowsToTable('iati_document_link' , $docUrl);

            //update document link category
            $docCat['@code'] = $data['document_category_code'];
            $docCat['text'] = '';
            $docCat['@xml_lang'] = $default['language']; 
            $docCat['document_link_id'] = $documentId;
            $model->updateRowsToTable('iati_document_link/category' , $docCat);

             //update document link title
            $docTitle['text'] = $data['document_title'];
            $docTitle['@xml_lang'] = $default['language']; 
            $docTitle['document_link_id'] = $documentId;
            $model->updateRowsToTable('iati_document_link/title' , $docTitle);
            
        }
        
        //Update Location
        if($data['location_name']){
            $loc['activity_id'] = $activityId;
            $locId = $model->updateRowsToTable('iati_location' , $loc);
            
            //update Location Name
            $locName['@xml_lang'] = $default['language'];
            $locName['text'] = $data['location_name'];
            $locName['location_id'] = $locId;
            $model->updateRowsToTable('iati_location/name' , $locName);
        }
        
        //Update Budget
        foreach($data['budget'] as $budgetData){
            if($budgetData['amount']){
                $budget['@type'] = '';
                $budget['activity_id'] = $activityId;
                $budgetId = $model->updateRowsToTable('iati_budget' , $budget);
                
                //update Budget value
                $budValue['text'] = $budgetData['amount'];
                $budValue['@value_date'] = $budgetData['signed_date'];
                $budValue['@currency'] = $budgetData['currency'];
                $budValue['budget_id'] = $budgetId;
                $model->updateRowsToTable('iati_budget/value' , $budValue);
                
                //update Budget Start
                $budStart['@iso_date'] = $budgetData['start_date'];
                $budStart['budget_id'] = $budgetId;
                $model->updateRowsToTable('iati_budget/period_start' , $budStart);
                
                //update Budget End
                $budEnd['@iso_date'] = $budgetData['end_date'];
                $budEnd['budget_id'] = $budgetId;
                $model->updateRowsToTable('iati_budget/period_end' , $budEnd);            
            }
        }
        
        //Update Transaction
        // Commitment
        foreach($data['commitment'] as $commitment){
            if($commitment['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->updateRowsToTable('iati_transaction' , $tran);
                //update transaction type
                $tranType['@code'] = 1;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/transaction_type' , $tranType);
                 //update transaction value
                $tranValue['@currency'] = $commitment['currency'];
                $tranValue['text'] = $commitment['amount'];
                $tranValue['@value_date'] = $commitment['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
        //Update Incomming Fund
        foreach($data['incommingFund'] as $incommingFund){
            if($incommingFund['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->updateRowsToTable('iati_transaction' , $tran);
                //update transaction type
                $tranType['@code'] = 5;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/transaction_type' , $tranType);
                //update transaction value
                $tranValue['@currency'] = $incommingFund['currency'];
                $tranValue['text'] = $incommingFund['amount'];
                $tranValue['@value_date'] = $incommingFund['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/value' , $tranValue);
            }
        }
        //Update Expenditure
        foreach($data['expenditure'] as $expenditure){
            if($expenditure['amount']){
                $tran['activity_id'] = $activityId;
                $transactionId = $model->updateRowsToTable('iati_transaction' , $tran);
                //update transaction type
                $tranType['@code'] = 4;//@todo check
                $tranType['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/transaction_type' , $tranType);
                //update transaction value
                $tranValue['@currency'] = $expenditure['currency'];
                $tranValue['text'] = $expenditure['amount'];
                $tranValue['@value_date'] = $expenditure['start_date'];
                $tranValue['transaction_id'] = $transactionId;
                $model->updateRowsToTable('iati_transaction/value' , $tranValue);

            }
        }
        
        //Update Sector
        $sector['@code'] = $data['sector'];
        $sector['@vocabulary'] = 3; // @todo check
        $sector['activity_id'] = $activityId;
        $model->updateRowsToTable('iati_sector' , $sector);
    }
}
