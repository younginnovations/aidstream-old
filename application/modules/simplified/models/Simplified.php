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
    public function addActivity($data , $default)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        //var_dump($data);exit;
        $model = new Model_Wep();
        $modelActivity =  new Model_Activity();
        //Create activity and its defaults
        $iatiIdentifier['activity_identifier'] = $data['identifier'];
        $iatiIdentifier['iati_identifier'] = $default['reporting_org_ref']."-".trim($data['identifier']);
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
        //Create Document
        if($data['document']){
            foreach($data['document'] as $document){
                if($this->hasValue($document)){
                    if($document['url']){
                        $docUrl['@url'] = $document['url'];
                        $docUrl['activity_id'] = $activityId;
                        $documentId = $model->insertRowsToTable('iati_document_link' , $docUrl);
                        
                        //Insert document link category
                        if($document['category_code']){
                            $docCat['@code'] = $document['category_code'];
                            $docCat['text'] = '';
                            $docCat['@xml_lang'] = $default['language']; 
                            $docCat['document_link_id'] = $documentId;
                            $model->insertRowsToTable('iati_document_link/category' , $docCat);
                        }
            
                         //Insert document link title
                        if($document['title']){
                            $docTitle['text'] = $document['title'];
                            $docTitle['@xml_lang'] = $default['language']; 
                            $docTitle['document_link_id'] = $documentId;
                            $model->insertRowsToTable('iati_document_link/title' , $docTitle);
                        }          
                    }
                }
            }
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
        if($data['budget']){
            foreach($data['budget'] as $budgetData){
                if($this->hasValue($budgetData)){
                    $budget['@type'] = '';
                    $budget['activity_id'] = $activityId;
                    $budgetId = $model->insertRowsToTable('iati_budget' , $budget);
                    
                    //Insert Budget value
                    if($budgetData['amount']){
                        $budValue['text'] = $budgetData['amount'];
                        $budValue['@value_date'] = $budgetData['signed_date'];
                        $budValue['@currency'] = $budgetData['currency'];
                        $budValue['budget_id'] = $budgetId;
                        $model->insertRowsToTable('iati_budget/value' , $budValue);
                    }
                    
                    //Insert Budget Start
                    if($budgetData['start_date']){
                        $budStart['@iso_date'] = $budgetData['start_date'];
                        $budStart['budget_id'] = $budgetId;
                        $model->insertRowsToTable('iati_budget/period_start' , $budStart);
                    }
                    
                    //Insert Budget End
                    if($budgetData['end_date']){
                        $budEnd['@iso_date'] = $budgetData['end_date'];
                        $budEnd['budget_id'] = $budgetId;
                        $model->insertRowsToTable('iati_budget/period_end' , $budEnd);
                    }
                }
            }
        }
        
        //Create Transaction
        // Commitment
        if($data['commitment']){
            foreach($data['commitment'] as $commitment){
                if($this->hasValue($commitment)){
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
        }
        //Incomming Fund
        if($data['incommingFund']){
            foreach($data['incommingFund'] as $incommingFund){
                if($this->hasValue($incommingFund)){
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
        }
        // Expenditure
        if($data['expenditure']){
            foreach($data['expenditure'] as $expenditure){
                if($this->hasValue($expenditure)){
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
        
        return $activityId;
        
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
        if(!empty($participatingOrgObj)){
            $count = 0;
            foreach($participatingOrgObj as $participatingOrg){
                $data['funding_org'][$count] = $participatingOrg->getAttrib('text');
                $count++;
            }
        }
        
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
        $docdata = $docObj[0]->getattribs();
        $count = 0;
        if($docdata['id']){
            foreach($docObj as $document){
                $data['document'][$count]['id'] = $document->getAttrib('id');
                $data['document'][$count]['url'] = $document->getAttrib('@url');
                // get document category
                $docCat = $document->getElementsByType('Category');
                if(!empty($docCat)){
                    $data['document'][$count]['category_id'] = $docCat[0]->getAttrib('id');
                    $data['document'][$count]['category_code'] = $docCat[0]->getAttrib('@code');
                }
                // get document title
                $docTitle = $document->getElementsByType('Title');
                if(!empty($docTitle)){
                    $data['document'][$count]['title_id'] = $docTitle[0]->getAttrib('id');
                    $data['document'][$count]['title'] = $docTitle[0]->getAttrib('text');
                }
                $count++;
            }
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
        $budgetData = $budgetObj[0]->getAttribs();
        $count = 0;
        if($budgetData['id']){
            foreach($budgetObj as $budget){
                $data['budget'][$count]['id'] = $budget->getAttrib('id');
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
                    $data[$type][$count]['id'] = $transaction->getAttrib('id');
                    $data[$type][$count]['value_id'] = $transactionVal[0]->getAttrib('id');
                    $data[$type][$count]['currency'] = $transactionVal[0]->getAttrib('@currency');
                    $data[$type][$count]['start_date'] = $transactionVal[0]->getAttrib('@value_date');
                    $data[$type][$count]['amount'] = $transactionVal[0]->getAttrib('text');
                }
            }
        }
        
        // Get sector
        $sectorObj = $activity->getElementsByType(Iati_Activity_Element::TYPE_SECTOR);
        if(!empty($sectorObj)){
            $count = 0;
            foreach($sectorObj as $sector){
                $data['sector'][$count]['sector'] = $sector->getAttrib('@code');
                $count++;
            }
        }
        return $data;
    }
    
    public function updateActivity($data , $default)
    {
       // var_dump($data);exit;
        $model = new Model_Wep();
        $activityId = $data['activity_id'];
        
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
        }

        //Update Description
        if($data['description_id']){
            $description['@xml_lang'] = $default['language'];
            $description['text'] = $data['description'];
            $description['id'] = $data['description_id'];
            $model->updateRowsToTable('iati_description' , $description);
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
            $startDate['@iso_date'] = $data['start_date'];
            $startDate['@xml_lang'] = $default['language'];
            $startDate['id'] = $data['start_date_id'];
            $model->updateRowsToTable('iati_activity_date' , $startDate);
        }

        //Update End date
        if($data['end_date_id']){
            $endDate['@iso_date'] = $data['end_date'];
            $endDate['@xml_lang'] = $default['language'];
            $endDate['text'] = '';
            $endDate['id'] = $data['end_date_id'];
            $model->updateRowsToTable('iati_activity_date' , $endDate);
        }

        //Update Document
        if($data['document']){
            foreach($data['document'] as $documentData){
                if($documentData['id']){
                    $docUrl = array();
                    $docUrl['@url'] = $documentData['url'];
                    $docUrl['id'] = $documentData['id'];
                    $documentId = $model->updateRowsToTable('iati_document_link' , $docUrl);
        
                    //update document link category
                    if($documentData['category_id']){
                        $docCat = array();
                        $docCat['@code'] = $documentData['category_code'];
                        $docCat['@xml_lang'] = $default['language']; 
                        $docCat['id'] = $documentData['category_id'];
                        $model->updateRowsToTable('iati_document_link/category' , $docCat);
                    }
        
                    //update document link title
                    if($documentData['title_id']){
                        $docTitle = array();
                        $docTitle['text'] = $documentData['title'];
                        $docTitle['@xml_lang'] = $default['language']; 
                        $docTitle['id'] = $documentData['title_id'];
                        $model->updateRowsToTable('iati_document_link/title' , $docTitle);
                    }
                    
                } elseif($this->hasValue($documentData)){
                    $docUrl = array();
                    $docUrl['@url'] = $documentData['url'];
                    $docUrl['activity_id'] = $activityId;
                    $documentId = $model->insertRowsToTable('iati_document_link' , $docUrl);
                    
                    //Insert document link category
                    $docCat = array();
                    $docCat['@code'] = $documentData['category_code'];
                    $docCat['text'] = '';
                    $docCat['@xml_lang'] = $default['language']; 
                    $docCat['document_link_id'] = $documentId;
                    $model->insertRowsToTable('iati_document_link/category' , $docCat);
        
                    //Insert document link title
                    $docTitle = array();
                    $docTitle['text'] = $documentData['title'];
                    $docTitle['@xml_lang'] = $default['language']; 
                    $docTitle['document_link_id'] = $documentId;
                    $model->insertRowsToTable('iati_document_link/title' , $docTitle);
                }
            }
        }    
        //Update Location
        if($data['location_id']){
            /*
            $loc['activity_id'] = $activityId;
            $locId = $model->updateRowsToTable('iati_location' , $loc);
            */
            //update Location Name
            $locName['@xml_lang'] = $default['language'];
            $locName['text'] = $data['location_name'];
            $locName['id'] = $data['location_id'];
            $model->updateRowsToTable('iati_location/name' , $locName);
        }

        //Update Budget
        if($data['budget']){
            foreach($data['budget'] as $budgetData){
                if($budgetData['id']){
                    //update Budget value
                    if($budgetData['value_id']){
                        $budValue = array();
                        $budValue['text'] = $budgetData['amount'];
                        $budValue['@value_date'] = $budgetData['signed_date'];
                        $budValue['@currency'] = $budgetData['currency'];
                        $budValue['id'] = $budgetData['value_id'];
                        $model->updateRowsToTable('iati_budget/value' , $budValue);
                    }
                    
                    //update Budget Start
                    if($budgetData['start_id']){
                        $budStart = array();
                        $budStart['@iso_date'] = $budgetData['start_date'];
                        $budStart['id'] = $budgetData['start_id'];
                        $model->updateRowsToTable('iati_budget/period_start' , $budStart);
                    }
                    
                    //update Budget End
                    if($budgetData['end_id']){
                        $budEnd = array();
                        $budEnd['@iso_date'] = $budgetData['end_date'];
                        $budEnd['id'] = $budgetData['end_id'];
                        $model->updateRowsToTable('iati_budget/period_end' , $budEnd);
                    }
                } elseif($this->hasValue($budgetData)) {
                    $budget = array();
                    $budget['@type'] = '';
                    $budget['activity_id'] = $activityId;
                    $budgetId = $model->insertRowsToTable('iati_budget' , $budget);
    
                    //Insert Budget value
                    $budValue = array();
                    $budValue['text'] = $budgetData['amount'];
                    $budValue['@value_date'] = $budgetData['signed_date'];
                    $budValue['@currency'] = $budgetData['currency'];
                    $budValue['budget_id'] = $budgetId;
                    $model->insertRowsToTable('iati_budget/value' , $budValue);
                    
                    //Insert Budget Start
                    $budStart = array();
                    $budStart['@iso_date'] = $budgetData['start_date'];
                    $budStart['budget_id'] = $budgetId;
                    $model->insertRowsToTable('iati_budget/period_start' , $budStart);
                    
                    //Insert Budget End
                    $budEnd = array();
                    $budEnd['@iso_date'] = $budgetData['end_date'];
                    $budEnd['budget_id'] = $budgetId;
                    $model->insertRowsToTable('iati_budget/period_end' , $budEnd);
                }
            }
        }
        
        //Update Transaction
        // Commitment
        if($data['commitment']){
            foreach($data['commitment'] as $commitment){
                if($commitment['value_id']){
                     //update transaction value
                    $tranValue = array();
                    $tranValue['@currency'] = $commitment['currency'];
                    $tranValue['text'] = $commitment['amount'];
                    $tranValue['@value_date'] = $commitment['start_date'];
                    $tranValue['id'] = $commitment['value_id'];
                    $model->updateRowsToTable('iati_transaction/value' , $tranValue);
                } elseif($this->hasValue($commitment)) {
                    $tran = array();
                    $tran['activity_id'] = $activityId;
                    $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                    //insert transaction type
                    $tranType = array();
                    $tranType['@code'] = 1;//@todo check
                    $tranType['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                     //insert transaction value
                    $tranValue= array();
                    $tranValue['@currency'] = $commitment['currency'];
                    $tranValue['text'] = $commitment['amount'];
                    $tranValue['@value_date'] = $commitment['start_date'];
                    $tranValue['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/value' , $tranValue);
                }
            }
        }    
        //Update Incomming Fund
        if($data['incommingFund']){
            foreach($data['incommingFund'] as $incommingFund){
                if($incommingFund['value_id']){
                    //update transaction value
                    $tranValue = array();
                    $tranValue['@currency'] = $incommingFund['currency'];
                    $tranValue['text'] = $incommingFund['amount'];
                    $tranValue['@value_date'] = $incommingFund['start_date'];
                    $tranValue['id'] = $incommingFund['value_id'];
                    $model->updateRowsToTable('iati_transaction/value' , $tranValue);
                } elseif($this->hasValue($incommingFund)) {
                    $tran = array();
                    $tran['activity_id'] = $activityId;
                    $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                    //update transaction type
                    $tranType = array();
                    $tranType['@code'] = 5;
                    $tranType['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
                    //update transaction value
                    $tranValue = array();
                    $tranValue['@currency'] = $incommingFund['currency'];
                    $tranValue['text'] = $incommingFund['amount'];
                    $tranValue['@value_date'] = $incommingFund['start_date'];
                    $tranValue['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/value' , $tranValue);
                }
            }
        }
        //Update Expenditure
        if($data['expenditure']){
            foreach($data['expenditure'] as $expenditure){
                if($expenditure['value_id']){
                    $tranValue = array();
                    $tranValue['@currency'] = $expenditure['currency'];
                    $tranValue['text'] = $expenditure['amount'];
                    $tranValue['@value_date'] = $expenditure['start_date'];
                    $tranValue['id'] = $expenditure['value_id'];
                    $model->updateRowsToTable('iati_transaction/value' , $tranValue);
                } elseif($this->hasValue($expenditure)) {
                    $tran = array();
                    $tran['activity_id'] = $activityId;
                    $transactionId = $model->insertRowsToTable('iati_transaction' , $tran);
                    //update transaction type
                    $tranType = array();
                    $tranType['@code'] = 4;
                    $tranType['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/transaction_type' , $tranType);
    
                    //update transaction value
                    $tranValue = array();
                    $tranValue['@currency'] = $expenditure['currency'];
                    $tranValue['text'] = $expenditure['amount'];
                    $tranValue['@value_date'] = $expenditure['start_date'];
                    $tranValue['transaction_id'] = $transactionId;
                    $model->insertRowsToTable('iati_transaction/value' , $tranValue);
                }
            }
        }
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
    }
    
    public function hasValue($data)
    {
        foreach($data as $value){
            if($value) return true;
        }
        return false;
    }
}