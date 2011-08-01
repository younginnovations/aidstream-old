<?php
class Iati_WEP_ExtractDataTest extends PHPUnit_Framework_TestCase
{
    public function testExtractDataFromElementTree()
    {
        $initial = array(
                'code' => '34',
                'currency' => '363',
                'xml_lang' => '54',
        ); 
        $activity = new Iati_WEP_Activity_Elements_Activity();
        $activity->setAttributes(array('activity_id' => '2'));
        
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($activity);
        
        $transaction = new Iati_WEP_Activity_Elements_Transaction ();
        $transaction->setAttributes($initial);
        $transactionType = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
        $transactionType-> setAttributes($initial); 
        $providerOrg = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();
        $providerOrg-> setAttributes($initial);
        
        $transaction1 = new Iati_WEP_Activity_Elements_Transaction ();
        $transaction1->setAttributes($initial);
        $transactionType1 = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
        $transactionType1-> setAttributes($initial); 
        $providerOrg1 = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();
        $providerOrg1-> setAttributes($initial); 
        
        $registryTree->addNode($transaction, $activity); 
        $registryTree->addNode($transactionType, $transaction);
        $registryTree->addNode($providerOrg, $transaction);
        $registryTree->addNode($providerOrg, $transaction);
        $registryTree->addNode($transactionType, $providerOrg); 
        
        $registryTree->addNode($transaction1, $activity); 
        $registryTree->addNode($transactionType1, $transaction1);
        
        $registryTree->addNode($providerOrg, $transaction1);
        $registryTree->addNode($providerOrg1, $transaction1);
        
        $registryTree->addNode($providerOrg, $transaction1);
        $registryTree->addNode($transactionType1, $providerOrg); 
        
//        print_r($registryTree->xml());exit;
        $classname = 'Iati_Activity_Element_Activity';
        $element = new $classname ();
        $data = $activity->getCleanedData();
        $element->setAttribs($data);
        $factory = new Iati_WEP_Activity_TransactionFactory();
        $a = $factory->cleanData($activity, $element);
        
        $b = $factory->extractData($a);
        print_r($b->xml());exit;
        
    }
    
    public function testNewTest()
    {
        $activity = new Iati_Activity_Element_Activity();
        $activityAttribs = array(
            'xmlLang' => 'en',
            'defaultCurrency' => 'USD',
            'hierarchy' => '0',
            'lastUpdatedDatetime' => '2011-02-01',
        );
        $activity->setAttribs($activityAttribs);
        // Another technique for adding new element is 
        $reportingOrg = $activity->createElement('reportingOrg');
        $activity->addElement($reportingOrg);
        $activity->addElement('participatingOrg');
        
        $factory = new Iati_WEP_Activity_TransactionFactory();
        $b = $factory->extractData($activity);
        print_r($b->xml());exit;
        
//        print_r($activity);
    }
    
    public function createRegistryTree()
    {
        $initial = array(
                'code' => '34',
                'currency' => '363',
                'xml_lang' => '54',
        ); 
        $activity = new Iati_WEP_Activity_Elements_Activity();
        $activity->setAttributes(array('activity_id' => '2'));
        
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($activity);
        
        $transaction = new Iati_WEP_Activity_Elements_Transaction ();
        $transaction->setAttributes($initial);
        $transactionType = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
        $transactionType-> setAttributes($initial); 
        $providerOrg = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();
        $providerOrg-> setAttributes($initial); 
        
        $transaction1 = new Iati_WEP_Activity_Elements_Transaction ();
        $transaction1->setAttributes($initial);
        $transactionType1 = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
        $transactionType1-> setAttributes($initial); 
        $providerOrg1 = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();
        $providerOrg1-> setAttributes($initial); 
        
        $registryTree->addNode($transaction, $activity); 
        $registryTree->addNode($transactionType, $transaction);
        $registryTree->addNode($providerOrg, $transaction);
        $registryTree->addNode($transaction1, $providerOrg); 
        
        $registryTree->addNode($transaction1, $activity); 
        $registryTree->addNode($transactionType1, $transaction1);
        $registryTree->addNode($providerOrg1, $transaction1);
        
        $classname = 'Iati_Activity_Element_Activity';
        $element = new $classname ();
        $data = $activity->getCleanedData();
        $element->setAttribs($data);
//        print_r($element);exit;
        $factory = new Iati_WEP_Activity_TransactionFactory();
//        $element = null;
       $a = $factory->cleanData($activity, $element);
       return $a;
    }
}