<?php

class Iati_WEP_Activity_ActivityDateFactory extends Iati_WEP_Activity_BaseFactory
{
    public function __construct()
    {
        
        parent :: __construct();
        
    }
    
    public function factory($objectType = 'ActivityDate', $data = array())
    {
        $this->globalObject = $this->getRootNode();
        if($data){
            $this->globalObject = $this->getRootNode();
            foreach ($data as $key => $values){
                if(is_array ($values)){
                    $tree = $this->createObjects ($objectType, $this->globalObject, $values);
                }
            }
        }
        else{
            $tree = $this->createObjects ($objectType,$this->globalObject);
        }

        return $tree;
    }
}