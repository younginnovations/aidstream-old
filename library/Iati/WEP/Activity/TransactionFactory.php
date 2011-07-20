<?php
class Iati_WEP_Activity_TransactionFactory
{
    protected $defaultValues = array();
    protected $globalObject;
    protected $initial;
    protected $data = array();

    public function __construct()
    {
        
        //        $this->accountActivity = $accountActivity;
    }
    
    /*
     * array( '0' => array(
     *                  'transaction' => array(
     *                                  'transaction_type' => array(
     *                                                          'text' => array(
     *                                                              '0' => 'blah',
     *                                                              '1' => 'blah',
     *                                                          ),
     *                                                          'code' => array(
     *                                                              '0' => 23,
 *                                                               '1' => 33,
*                                                               ),     
*                                        ),
*                                        'provider_org' => array(
*                                                           'text'
*                                         ),
     *                  ),
     *      )
     */

    public function factory($objectType = 'Transaction', $data = array())
    {
        $this->data = $data;
//        print_r($this->getFields('TransactionType'));exit;
        $function = 'create'.$objectType;
        $this->globalObject = $this->getRootNode();
        $tree = $this->$function();

        
        return $tree;
    }

    public function createTransaction(){
        $data = ($this->data) ? $this->getFields('Transaction') : '';
        
        $transaction = new Iati_WEP_Activity_Elements_Transaction ();
        $transaction->setAttributes($data);
        if($this->data){
            $flatArray = $this->flatArray($data);
            $transaction->setAttributes($flatArray);
            $transaction->validate();
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper ($transaction);
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        $registryTree->addNode ($dbWrapper, $this->globalObject);
        $transaction_type = $this->createTransactionType ($dbWrapper);
        $provider_org = $this->createProviderOrg ($dbWrapper);
        $receiver_org = $this->createReceiverOrg ($dbWrapper);
        $value = $this->createValue ($dbWrapper);
        return $registryTree;

    }

    public function createTransactionType($parent = null)
    {
        $data = ($this->data) ? $this->getFields('TransactionType') : $this->getInitialValues();
        $flatArray = $this->flatArray($data);//print_r($flatArray);exit;
        $transaction_type = new Iati_WEP_Activity_Elements_Transaction_TransactionType();
        $transaction_type->setAttributes($data);
        if($this->data){
            $transaction_type->validate($data);
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($transaction_type);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    public function createProviderOrg($parent = null)
    {
        $data = ($this->data) ? $this->getFields('ProviderOrg') : $this->getInitialValues();
        $provider_org = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg();
        $provider_org->setAttributes($this->getInitialValues());
        if($this->data){
            $provider_org->validate($data);
        }
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($provider_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }
    
    public function createReceiverOrg($parent = null)
    {
        $data = ($this->data) ? $this->getFields('ReceiverOrg') : $this->getInitialValues();
        $receiver_org = new Iati_WEP_Activity_Elements_Transaction_ReceiverOrg();
        $receiver_org->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($receiver_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    public function createValue($parent = null)
    {
        $data = ($this->data) ? $this->getFields('Value') : $this->getInitialValues();
        $value = new Iati_WEP_Activity_Elements_Transaction_Value();
        $value->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($value);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

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
        print_r($this->data);exit;
        $newArray = array();
        
        foreach($this->data as $key => $value){
            $key_array = explode('_', $key);
            if($key_array[0] == $class){
                array_shift($key_array);
                $newArray[implode("", $key_array)] = $value;
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
    
    /**
     * need to check if transaction exists for given transaction_id
     * @return unknown_type
     */
    public function getRowSet()
    {
        $registry = Iati_WEP_TreeRegistry::getInstance();
    }


}