<?php
class Iati_WEP_Activity_CollaborationTypeFactory extends Iati_WEP_Activity_BaseFactory
{
    public function __construct()
    {
        parent :: __construct();
    }

    public function factory($objectType = 'CollaborationType', $data = array())
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