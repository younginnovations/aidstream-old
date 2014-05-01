<?php  

class Model_CsvUpload 
{
    protected $inputFile;
    protected $data;
    protected $elementData;
    protected $error;
    protected $keys;
    protected $headerCount;
    protected $requiredHeaders = array('TransactionType', 'TransactionValue');
    protected $ignoredHeaders = array('Activity_Identifier', 'Activity_Title', 'Default_currency');
    protected $requiredInputs = array(
                                        'TransactionType' => array('code'), 
                                        'ProviderOrg' => array('text'),
                                        'ReceiverOrg' => array('text'),
                                        'TransactionValue' => array('value_date', 'text'),
                                        'Description' => array('text'),
                                        'TransactionDate' => array('iso_date'),
                                        'FlowType' => array('code'),
                                        'FinanceType' => array('code'),
                                        'AidType' => array('code'),
                                        'DisbursementChannel' => array('code'),
                                        'TiedStatus' => array('code')
                                    );

    
    /**
     * Set Input file
     */
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

    /**
     * Count number of headers from csv to determine simple or detailed transaction template 
     */
    public function countHeader()
    {
        $headerCount = 0;
        foreach ($this->data[0] as $transactionData) {
            if($transactionData) $headerCount += 1;
        }
        return $headerCount;
    }

    public function getErrors() {
        return $this->error;
    }

    /**
     * Prepare Detail Transaction Data  
     */    
    public function prepareDetailTransactionData() 
    {
        $header = array_flip(array_shift($this->data));
        $headerKeys = array_keys($header);
        $count = 0;
        foreach ($this->data as $data) {
            foreach ($data as $key => $value) {
                $value = trim($value);
                if (in_array($headerKeys[$key], $this->ignoredHeaders)) continue;
                if (empty($headerKeys[$key])) continue;

                $parent = strstr($headerKeys[$key], '-', true);
                $child = preg_replace('/-/', '', strstr($headerKeys[$key], '-'), 1);
                
                if ($parent == "Transaction") { 
                    $this->elementData[$count][$child] = $value;
                } else {
                    $this->elementData[$count][$parent][$child] = $value;
                }
            }

            $this->checkRequiredFields($count, $this->elementData[$count]);
            $count++;
        }

        $this->checkRefDuplication();
        return $count;
    }

    /**
     * Check Required Inputs for Detail Transaction 
     */
    public function checkRequiredFields($count, $elementData) {
        foreach ($elementData as $header => $data) {
            foreach ($data as $key => $value) {
                if (in_array($header, $this->requiredHeaders)) {
                    if (in_array($key, $this->requiredInputs[$header]) && $value) {
                        $this->validateDetailTransactionData($header, $key, $count, $value);
                    } elseif (!in_array($key, $this->requiredInputs[$header]) && ($value || !$value)) {
                        $this->validateDetailTransactionData($header, $key, $count, $value);
                    } else {
                        $this->error[$count][]['message'] = $header . '-' . $key .' is a required field.';
                    }
                } else {
                    if (in_array($key, $this->requiredInputs[$header]) && !$value) {
                        $state = true; 
                    } elseif (in_array($key, $this->requiredInputs[$header]) && $value) {
                        $state = false;
                        $this->validateDetailTransactionData($header, $key, $count, $value);
                    } elseif (!in_array($key, $this->requiredInputs[$header]) && $value && $state) {
                        $this->error[$count][]['message'] = 'Cannot set ' . $header . '-' . $key . ' without setting ' . $header . '-' . $this->requiredInputs[$header][0] . '.';
                    }
                }
            }
        }
    }

    /**
     * Validate values for Transaction
     */
    public function validateDetailTransactionData($parent, $child, $count, $value)
    {
        $model = new Model_Wep();
        switch ($child) {
            case 'code':
                $code = $model->getCodeandName($parent, 1);
                if (in_array(strtoupper($value), $code)) {
                    $this->elementData[$count][$parent][$child] = array_search(strtoupper($value), $code);
                } else {
                    $this->error[$count][]['message'] = "Invalid " . $parent . "-code. Please use proper code."; 
                }
                break;
            
            case 'xml_lang':
                $xml_lang = $model->getCodeandName('Language', 1);
                if (in_array(strtolower($value), $xml_lang)) {
                    $this->elementData[$count][$parent][$child] = array_search(strtolower($value), $xml_lang);
                } else {
                    $this->error[$count][]['message'] = "Invalid " . $parent . "-xml_lang code. Please use a valid language code.";
                }
                break;

            case 'iso_date':
            case 'value_date':
                $date = date_parse($value);
                if (!checkdate($date["month"], $date["day"], $date["year"])){ 
                    $this->error[$count][]['message'] = $parent . "-" . $child . " must be in date format.";
                } else {
                    $this->elementData[$count][$parent][$child] = date('Y-m-d', strtotime($value));
                }
                break;

            default:
                break;
        }
    }

    /**
     * Check Internal Ref duplication for Detail Transaction
     */
    public function checkRefDuplication() {
        $refArray = array();
        foreach ($this->elementData as $data) {
            $value = trim($data['ref']);
            if ($value) {
                array_push($refArray, $value);
            }
        }

        $refError = array_diff_assoc($refArray, array_unique($refArray));
        foreach ($refError as $key => $value) {
            $this->error[$key+1][]['message'] = 'Internal Reference duplication. Please check your CSV file.';
        }
    }

    /**
     *  Upload Detail Data to Transaction
     */
    public function uploadDetailDataToTransaction($activityId)
    {
        $count = array();
        $count['total'] = $this->prepareDetailTransactionData();
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
                        $this->elementData[$key]['ProviderOrg']['id'] = $row['ProviderOrg']['id'];
                        $this->elementData[$key]['ReceiverOrg']['id'] = $row['ReceiverOrg']['id'];
                        $this->elementData[$key]['TransactionValue']['id'] = $row['TransactionValue']['id'];
                        $this->elementData[$key]['Description']['id'] = $row['Description']['id'];
                        $this->elementData[$key]['TransactionDate']['id'] = $row['TransactionDate']['id'];
                        $this->elementData[$key]['FlowType']['id'] = $row['FlowType']['id'];
                        $this->elementData[$key]['FinanceType']['id'] = $row['FinanceType']['id'];
                        $this->elementData[$key]['AidType']['id'] = $row['AidType']['id'];
                        $this->elementData[$key]['DisbursementChannel']['id'] = $row['DisbursementChannel']['id'];
                        $this->elementData[$key]['TiedStatus']['id'] = $row['TiedStatus']['id'];
                        
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

    /**
     * Upload Simple Data to Transaction
     */
    public function uploadSimpleDataToTransaction($activityId)
    {
        $count = array();
        $count['total'] = $this->prepareSimpleTransactionData();
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
    
    public function prepareSimpleTransactionData()
    {
        $count = 0;
        $header = array_shift($this->data);
        
        // change header values to keys for later user.
        $this->keys = $keys = array_change_key_case(array_flip(preg_replace('/ /', '', $header)));
        
        $this->validateSimpleTransactionData(); // @todo Validation can be done by using elements form
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
    
    public function validateSimpleTransactionData($data , $count)
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