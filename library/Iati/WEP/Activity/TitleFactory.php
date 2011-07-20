<?php
class Iati_WEP_Activity_TitleFactory //extends Iati_WEP_Activity_BaseFactory
{
//    protected $accountActivity;
    protected $defaultValues;
    protected $globalObject;
    protected $initial;
    protected $data;
    
    public function __construct($accountActivity)
    {
        $this->accountActivity = $accountActivity;
    }
    
    public function factory($objectType = 'Transaction', $data = array())
    {
        $this->data = $data;
        $function = 'create'.$objectType;
        $this->globalObject = $this->getGlobalObj();
        $tree = $this->$function();
        
        return $tree;
    }
    
    public function createTitle()
    {
        if($data){
            foreach($data as $key => $eachData){
                
            }
        }   
        else{
            $title = new Iati_WEP_Activity_Elements_Title();
            $title->setAttributes($this->getInitialValues());
            $dbWrapper = new Iati_WEP_Activity_DbWrapper($title);
            
            $registryTree = Iati_WEP_TreeRegistry::getInstance();
            $registryTree->addNode($dbWrapper, $this->getGlobalObj());
            return $registryTree;
        }
        
    }
    
    public function setInitialValues($initial)
    {
        $this->defaultValues = $initial;
    }
    
    public function getInitialValues()
    {
        return $this->defaultValues;
    }
    
    public function getRootNode()
    {
        $registry = Iati_WEP_TreeRegistry::getInstance();
        return $registry->getRootNode();
    }
}