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
        $fp = fopen($this->inputFile , 'r');
        while($row = fgetcsv($fp)){
            $this->data[] = $row;
        }
    }
    
    public function uploadDataToTransaction($activityId)
    {
        $count = $this->prepareTransactionData();
        if(empty($this->error)){
            $element = new Iati_Aidstream_Element_Activity_Transaction();
            //Validation using element's form. some validation should be done previously if this is used
            //$element->setData($this->elementData);
            //$form = $element->getForm();
            //$form->validate();
            //var_dump($form->getMessages());exit;
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
        
        foreach($this->data as $transactionData){// Prepare date for element.       
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
            
            $this->elementData[$count]['ref'] = $transactionData[$keys['internalreference']];

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
            
            $count++;
        }
    }
}
