<?php
class Iati_WEP_UserAccountDisplayField
{
    protected $add = '0';
    protected $edit = '0';
    protected $delete = '0';
    protected $view = '0';
    
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