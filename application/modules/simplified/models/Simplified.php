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

             //Insert document link category
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
}
