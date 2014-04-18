<?php

class Model_CsvUpload
{
    protected $inputFile;
    protected $data;
    protected $elementData;
    protected $error;
    protected $keys;
    
    public function getErrors()
    {
        return $this->error;
    }
    
    public function setInputFile($file)
    {
        $this->inputFile = $file;
    }
    
    /**
     * Reads csv from file
     */
    public function readCsv()
    {
        ini_set("auto_detect_line_endings", true);
        $fp = fopen($this->inputFile , 'r');
        while($row = fgetcsv($fp)){
            $this->data[] = $row;
        }
    }
    
    public function uploadDataToTransaction($activityId)
    {
        $count = array();
        $count['total'] = $this->prepareTransactionData();
        $element = new Iati_Aidstream_Element_Activity_Transaction();
        
        $result = $element->fetchData($activityId, true);
        $result = Iati_ElementSorter::sortElementsData($result, array('TransactionDate' =>'@iso_date'), array('TransactionValue' => '@value_date'));
        
        // Update if existing transaction by compairing 'internal reference'
        $count['update'] = 0;
        $duplicate = 0;
        $transactionKey = 0;
        foreach ($this->elementData as $key => $data) {
            $refCount = 0;
            foreach($result as $row) {
                if (strtolower($data['ref']) == strtolower($row['@ref']) && !empty($data['ref'])) {
                    if ($refCount == 0) {
                        $this->elementData[$key]['id'] = $row['id'];
                        $this->elementData[$key]['TransactionType']['id'] = $row['TransactionType']['id'];
                        $this->elementData[$key]['TransactionValue']['id'] = $row['TransactionValue']['id'];
                        $this->elementData[$key]['ProviderOrg']['id'] = $row['ProviderOrg']['id'];
                        $this->elementData[$key]['ReceiverOrg']['id'] = $row['ReceiverOrg']['id'];
                        $this->elementData[$key]['TransactionDate']['id'] = $row['TransactionDate']['id'];
                        $this->elementData[$key]['Description']['id'] = $row['Description']['id'];
                        $count['update'] += 1;  // Transaction Update count
                        $refCount = 1;
                    } elseif ($refCount == 1) {
                        $duplicate += 1;
                        $transactionKey = $key;
                    }        
                }
            }
        }

        // Transaction add count
        $count['add'] = $count['total'] - $count['update'];
        
        if($duplicate >= 1) {
            $this->error[$transactionKey][]['message'] = "Cannot update transaction. Internal reference duplication on your 
                                                        existing transactions. Please use a different internal reference or 
                                                        check your existing transactions.";
        }
        
        if(empty($this->error)){
            $element->save($this->elementData , $activityId);
            
            return $count;
        } else {
            return false;
        }
        
    }
    
    public function prepareTransactionData()
    {
        $count = 0;
        $header = array_shift($this->data);
        
        // change header values to keys for later user.
        $this->keys = $keys = array_change_key_case(array_flip(preg_replace('/ /', '', $header)));
        
        $this->validateTransactionData(); // @todo Validation can be done by using elements form
        if(!empty($this->error)) return false; // In case of validation failure the error array is set.
        
        foreach($this->data as $transactionData){ // Prepare date for element.       
            $value = '';
            if($transactionData[$keys['incomingfund']]){
                $value = $transactionData[$keys['incomingfund']];
                $type = 5;
            }
            if($transactionData[$keys['expenditure']]){
                $value = $transactionData[$keys['expenditure']];
                $type = 4;
            }
            if ($transactionData[$keys['disbursement']]){
                $value = $transactionData[$keys['disbursement']];
                $type = 2;
            }
            if ($transactionData[$keys['commitment']]){
                $value = $transactionData[$keys['commitment']];
                $type =1;
            }
            $transactionDate = date( 'Y-m-d' , strtotime($transactionData[$keys['transactiondate']]));
            
            $this->elementData[$count]['ref'] = trim($transactionData[$keys['internalreference']]);
            $this->elementData[$count]['id'] = null;
            //Transaction type
            $this->elementData[$count]['TransactionType']['code']= $type;
            
            //Transaction Value. use transaction date as value date.
            $this->elementData[$count]['TransactionValue']['text'] = $value;
            $this->elementData[$count]['TransactionValue']['value_date'] = $transactionDate;
            
            //Provider Organisation
            $this->elementData[$count]['ProviderOrg']['text'] = $transactionData[$keys['providerorgname']];
            $this->elementData[$count]['ProviderOrg']['ref'] = $transactionData[$keys['providerorgreference']];
            
            //Receiver Organisation
            $this->elementData[$count]['ReceiverOrg']['text'] = $transactionData[$keys['receiverorgname']];
            $this->elementData[$count]['ReceiverOrg']['ref'] = $transactionData[$keys['receiverorgreference']];
            
            //Transaction date
            $this->elementData[$count]['TransactionDate']['iso_date'] = $transactionDate;

            //Description
            $this->elementData[$count]['Description']['text'] = $transactionData[$keys['description']];
            $count++;
        }
        
        return $count;
    }
    
    public function validateTransactionData($data , $count)
    {
        $count = 0;
        $keys = $this->keys;

        $multipleMessage = "Transaction amount (value) must be filled in only one of Incoming Fund,
                    Expenditure, Disbursement or Commitment";

        foreach($this->data as $transactionData){// Validate each instance at a time.
            $value = 0;
            if($transactionData[$keys['incomingfund']]){
                $value = $transactionData[$keys['incomingfund']];
                if(!is_numeric($value)){
                    $this->error[$count][]['message'] = "Transaction Value must be numeric";
                }
            }
            if($transactionData[$keys['expenditure']]){
                if($value){
                    $this->error[$count]['TransactionType']['message'] = $multipleMessage;
                }
                $value = $transactionData[$keys['expenditure']];
                if(!is_numeric($value)){
                    $this->error[$count][]['message'] = "Transaction Value must be numeric";
                }
            }
            if ($transactionData[$keys['disbursement']]){
                if($value){
                    $this->error[$count]['TransactionType']['message'] = $multipleMessage;
                }
                $value = $transactionData[$keys['disbursement']];
                if(!is_numeric($value)){
                    $this->error[$count][]['message'] = "Transaction Value must be numeric";
                }
            }
            if ($transactionData[$keys['commitment']]){
                if($value){
                    $this->error[$count]['TransactionType']['message'] = $multipleMessage;
                }
                $value = $transactionData[$keys['commitment']];
                if(!is_numeric($value)){
                    $this->error[$count][]['message'] = "Transaction Value must be numeric";
                }
            }
            
            // check if date is valid.
            if(!$transactionData[$keys['transactiondate']]){
                $this->error[$count][]['message'] = "Transaction Date cannot be empty";
            } else {
                $date = date_parse($transactionData[$keys['transactiondate']]);
                if (!checkdate($date["month"], $date["day"], $date["year"])){ 
                    $this->error[$count][]['message'] = "Transaction Date must be in date format";
                }
            }

            if(!$value){// check if transaction value is provided.
                $this->error[$count][]['message'] = "Transaction Value is missing. Fill in any one of Incoming Fund,
                    Expenditure, Disbursement or Commitment";
            }
            
            if($transactionData[$keys['internalreference']]){
                $refArray[] = trim($transactionData[$keys['internalreference']]);
            }

            $count++;
        }

        // check for internal reference duplication. 
        $refError = array_diff_assoc($refArray, array_unique($refArray));
        foreach ($refError as $key => $value) {
            $this->error[$key+1][]['message'] = 'Internal Reference duplication. Please check your CSV file.';
        }
    }
        
}
