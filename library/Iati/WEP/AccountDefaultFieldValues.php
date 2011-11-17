<?php
class Iati_WEP_AccountDefaultFieldValues
{
    protected $language = 'en';
    protected $currency = 'USD';
    protected $reporting_org = '';
    protected $hierarchy = 0;
    protected $reporting_org_ref = '';
    protected $collaboration_type = '';
    protected $flow_type = '';
    protected $finance_type = '';
    protected $aid_type = '';
    protected $tied_status = '';

    public function setLanguage($data){
        $this->language = $data;
    }
    public function setCurrency($data){
        $this->currency = $data;
    }
    public function setReportingOrg($data){
        $this->reporting_org = $data;
    }
    
    public function setHierarchy($data){
        $this->hierarchy = $data;
    }
    
    public function setReportingOrgRef($data)
    {
        $this->reporting_org_ref = $data;   
    }
    
    public function setCollaborationType($collaborationType){
        $this->collaboration_type = $collaborationType;
    }
    
    public function setFlowType($flowType){
        $this->flow_type = $flowType;
    }
    
    public function setFinanceType($financeType){
        $this->finance_type = $financeType;
    }
    
    public function setAidType($aidType){
        $this->aid_type = $aidType;
    }
    
    public function setTiedStatus($tiedStatus){
        $this->tied_status = $tiedStatus;
    }
    
    public function getDefaultFields()
    {
        return get_object_vars($this);
    }
    
    /*public function encode($object)
    {
        return serialize($object);
    }*/
    public function decode($object){
        //@todo decode function i.e. check if there is any change in the default properties
    }
    
}