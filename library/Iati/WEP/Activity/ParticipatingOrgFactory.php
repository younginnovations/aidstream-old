<?php
class Iati_WEP_Activity_ParticipatingOrgFactory extends Iati_WEP_Activity_BaseFactory
{  
    public function __construct()
    {
        
        parent :: __construct();
        
    }
    
    public function factory($objectType = 'ParticipatingOrg', $data = array())
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
            $tree = $this->createObjects ($objectType);
        }

        return $tree;
    }
}