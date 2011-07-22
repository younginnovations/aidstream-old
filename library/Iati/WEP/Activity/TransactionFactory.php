<?php
class Iati_WEP_Activity_TransactionFactory
{
    protected $defaultValues = array();
    protected $globalObject;
    protected $initial;
    protected $data = array();
    protected $hasError = false;
    

    public function __construct()
    {
        
        //        $this->accountActivity = $accountActivity;
    }
    
    public function hasError()
    {
        return $this->hasError;
    }
    

    public function factory($objectType = 'Transaction', $data = array())
    {
        $this->data = $data;
        $function = 'create'.$objectType;
        $this->globalObject = $this->getRootNode();
        $tree = $this->$function();

        
        return $tree;
    }

    public function createTransaction(){
        
        $data = ($this->data) ? $this->getFields('Transaction') : '';
        
        $transaction = new Iati_WEP_Activity_Elements_Transaction ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        
        if($this->data){
            print_r($this->data);exit;
        }
        else{
            $transaction->setAttributes( $data );
            $registryTree->addNode ($transaction, $this->globalObject);
            $transaction_type = $this->createTransactionType ($transaction);
            $provider_org = $this->createProviderOrg ($transaction);
            $receiver_org = $this->createReceiverOrg ($transaction);
        }
        
        /*$transaction->setAttributes($data);
        if($this->data){
            $flatArray = $this->flatArray($data);
//            print_r($flatArray);exit;
            
//            $transaction->setAttributes($flatArray);
            foreach($flatArray['transaction_id'] as $eachTransaction)
            {
                foreach($eachTransaction as $eachId){
                    $transaction->setAttributes(array('transaction_id' => $eachId));
                    $dbWrapper = new Iati_WEP_Activity_DbWrapper ($transaction);
                    $registryTree = Iati_WEP_TreeRegistry::getInstance ();
                    $registryTree->addNode ($dbWrapper, $this->globalObject);
                }
//                $transaction->setAttributes($eachTransaction);
//                var_dump($eachTransaction[0]);
            }
//            print_r($registryTree->xml());exit;
        }*/
        
        //@todo change dbWrapper
//        $dbWrapper = new Iati_WEP_Activity_DbWrapper ($transaction);
        
        /*$transaction_type = $this->createTransactionType ($transaction);
        $provider_org = $this->createProviderOrg ($transaction);
        $receiver_org = $this->createReceiverOrg ($transaction);*/
//        $value = $this->createValue ($transaction);
        return $registryTree;

    }

    public function createTransactionType($parent = null)
    {
        $data = ($this->data) ? $this->getFields('TransactionType') : $this->getInitialValues();
        
        $transaction_type = new Iati_WEP_Activity_Elements_Transaction_TransactionType();
        
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        
        if($this->data){
            
        }
        else{
            $transaction_type->setAttributes( $data );
            $registryTree->addNode ($transaction_type, $parent);
        }
        
        return $registryTree;
        
        /*$transaction_type = new Iati_WEP_Activity_Elements_Transaction_TransactionType();
        $transaction_type->setAttributes($data);
        if($this->data){
            $flatArray = $this->flatArray($data);
//            print_r($flatArray);exit;
            $transaction_type->setAttributes($flatArray);
            $transaction_type->validate($data);
            if($transaction_type->hasErrors())
                $this->hasError = true;
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($transaction_type);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;*/
    }

    public function createProviderOrg($parent = null)
    {
        $data = $this->getInitialValues();
        
        $providerOrg = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
       
        if($this->data){
            
        }else{
            $providerOrg->setAttributes($data);
            $registryTree->addNode($providerOrg, $parent);   
        }
        
        return $registryTree;
        
    /*
        $data = ($this->data) ? $this->getFields('ProviderOrg') : $this->getInitialValues();
        $provider_org = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg();
        $provider_org->setAttributes($this->getInitialValues());
        if($this->data){
            $flatArray = $this->flatArray($data);
            $provider_org->setAttributes($flatArray);
            $provider_org->validate($data);
            if($provider_org->hasErrors())
                $this->hasError = true;
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($provider_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;*/
    }
    
    public function createReceiverOrg($parent = null)
    {
        $data = $this->getInitialValues();
        
        $receiverOrg = new Iati_WEP_Activity_Elements_Transaction_ReceiverOrg ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
       
        if($this->data){
            
        }else{
            $receiverOrg->setAttributes($data);
            $registryTree->addNode($receiverOrg, $parent);   
        }
        
        return $registryTree;
    
    
    /*
        $data = ($this->data) ? $this->getFields('ReceiverOrg') : $this->getInitialValues();
        $receiver_org = new Iati_WEP_Activity_Elements_Transaction_ReceiverOrg();
        $receiver_org->setAttributes($this->getInitialValues());
        if($this->data){
            $flatArray = $this->flatArray($data);
            $receiver_org->setAttributes($flatArray);
            $receiver_org->validate($data);
            if($receiver_org->hasErrors())
                $this->hasError = true;
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($receiver_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;*/
    }

    /*public function createValue($parent = null)
    {
        $data = ($this->data) ? $this->getFields('Value') : $this->getInitialValues();
        $value = new Iati_WEP_Activity_Elements_Transaction_Value();
        $value->setAttributes($this->getInitialValues());
        if($this->data){
            
            $flatArray = $this->flatArray($data);
            $value->setAttributes($flatArray);
            $value->validate($data);
            
            if($value->hasErrors())
                $this->hasError = true;
        }
        else{
            $dbWrapper = new Iati_WEP_Activity_DbWrapper($value);
            $registryTree = Iati_WEP_TreeRegistry::getInstance();
            $registryTree->addNode($dbWrapper, $parent);
        }
        return $registryTree;
    }*/

    public function setInitialValues($initial)
    {
        $this->defaultValues = $initial;
    }

    /**
     * default field values passed from the controller
     * @return unknown_type
     */
    public function getInitialValues()
    {
        return $this->defaultValues;
    }

    public function getRootNode()
    {
        $registry = Iati_WEP_TreeRegistry::getInstance();
        return $registry->getRootNode();
    }

    public function getFields($class)
    {
//        print_r($this->data);exit;
        $newArray = array();
        
        foreach($this->data as $key => $value){
            $key_array = explode('_', $key);
            if($key_array[0] == $class){
                array_shift($key_array);
                $newArray[implode("_", $key_array)] = $value;
            }
        }
        return $newArray;
    }    
    
    public function flatArray($array)
    {
        $array_values = array_values($array);
        $returnArray = array();
        foreach(array_keys($array_values[0]) as $i ){
            foreach($array as $key => $val){
                $returnArray[$key][$i] = $val[$i];
            }
        }
        return $returnArray;
    }
    


}