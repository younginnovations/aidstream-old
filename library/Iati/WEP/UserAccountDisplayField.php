<?php
class Iati_WEP_UserAccountDisplayField
{
    protected $add_activity = '0';
    protected $edit_activity = '0';
    protected $delete_activity = '0';
    protected $view_activities = '0';
    protected $add_activity_elements = '0';
    protected $edit_activity_elements = '0';
    
    
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