<?php
class Iati_WEP_AccountDisplayFieldGroup
{
    protected $title = '0';
    protected $activity_date = '0';
    protected $participating_organisation = '0';
    
    public function getProperties(){
        return get_object_vars($this);
    }
    
    public function setProperties($property){
        $this->$property = ((property_exists($this, $property))?'1': false);
        if(!$this->$property){
            throw new Exception("Property $property not found.");
        }

    }
    
   
    
   
}