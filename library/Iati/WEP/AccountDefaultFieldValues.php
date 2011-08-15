<?php
class Iati_WEP_AccountDefaultFieldValues
{
    protected $language = 'en';
    protected $currency = 'USD';
    protected $reporting_org = '';
    protected $hierarchy = 0;

    public function setLanguage($data){
        $this->language = $data;
    }
    public function setCurrency($data){
        $this->currency = $data;
    }
    public function setReporting_org($data){
        $this->reporting_org = $data;
    }
    
    public function setHierarchy($data){
        $this->hierarchy = $data;
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