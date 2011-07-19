<?php
class Iati_WEP_Activity_TransactionFactory
{
    protected $defaultValues = array();
    protected $globalObject;
    protected $initial;
    protected $data;

    public function __construct()
    {
        //        $this->accountActivity = $accountActivity;
    }

    public function factory($objectType = 'Transaction', $data = array())
    {
        $this->data = $data;
        $function = 'create'.$objectType;
//        var_dump($this->getRootNode());exit;
        $this->globalObject = $this->getRootNode();
        $tree = $this->$function();

        return $tree;
    }

    public function createTransaction(){

//        print_r($this->globalObject);exit;
        $transaction = new Iati_WEP_Activity_Elements_Transaction();
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($transaction);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
//        print_r($registryTree->xml());exit;
        $registryTree->addNode($dbWrapper, $this->globalObject);

        $transaction_type = $this->createTransactionType($dbWrapper);
        $provider_org = $this->createProviderOrg($dbWrapper);
        //        print_r($registryTree->getChildNodes($dbWrapper));exit;
        return $registryTree;

    }

    public function createTransactionType($parent = null)
    {
        $transaction_type = new Iati_WEP_Activity_Elements_Transaction_TransactionType();
        $transaction_type->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($transaction_type);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        //        print_r($registryTree->xml());exit;
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    public function createProviderOrg($parent = null)
    {
        $provider_org = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg();
        $provider_org->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($provider_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    /*public function setAccountActivity($array)
     {
     $this->accountActivity = $array;
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

    /**
     * need to check if transaction exists for given transaction_id
     * @return unknown_type
     */
    public function getRowSet()
    {
        $registry = Iati_WEP_TreeRegistry::getInstance();

    }


}