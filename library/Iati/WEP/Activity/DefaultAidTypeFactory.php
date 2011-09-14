<?php
class Iati_WEP_Activity_DefaultAidTypeFactory extends Iati_WEP_Activity_BaseFactory
{
    public function __construct()
    {
        parent :: __construct();
    }

    public function factory($objectType = 'DefaultAidType', $data = array())
    {
        $this->globalObject = $this->getRootNode();
        if($data){
            $tree = $this->createObjects ($objectType, $this->globalObject, $data);
        }
        else{
            $tree = $this->createObjects ($objectType, $this->globalObject);
        }

        return $tree;
    }
    
    
}