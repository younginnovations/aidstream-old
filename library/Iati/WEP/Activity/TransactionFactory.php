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
        $function = 'create'.$objectType;
        $this->globalObject = $this->getRootNode();
        $tree = $this->$function();

        
        return $tree;
    }

    public function createTransaction(){
        $transaction = new Iati_WEP_Activity_Elements_Transaction ();
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
        $data = ($this->data) ? $this->data['TransactionType'] : $this->getInitialValues();
        $transaction_type = new Iati_WEP_Activity_Elements_Transaction_TransactionType();
        $transaction_type->setAttributes($data);
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($transaction_type);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    public function createProviderOrg($parent = null)
    {
        $data = ($this->data) ? $this->data['ProviderOrg'] : $this->getInitialValues();
        $provider_org = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg();
        $provider_org->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($provider_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }
    
    public function createReceiverOrg($parent = null)
    {
        $data = ($this->data) ? $this->data['ReceiverOrg'] : $this->getInitialValues();
        $receiver_org = new Iati_WEP_Activity_Elements_Transaction_ReceiverOrg();
        $receiver_org->setAttributes($this->getInitialValues());
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($receiver_org);
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($dbWrapper, $parent);
        return $registryTree;
    }

    public function createValue($parent = null)
    {
        $data = ($this->data) ? $this->data['Value'] : $this->getInitialValues();
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
        /*$initial = array(
                    'code' => '26',
        );
        return $initial;*/
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