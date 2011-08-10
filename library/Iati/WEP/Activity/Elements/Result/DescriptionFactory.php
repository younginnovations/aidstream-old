<?php
class Iati_WEP_Activity_Elements_Result_DescriptionFactory extends Iati_WEP_Activity_BaseFactory
{
    public function __construct()
    {
        parent :: __construct();
    }



    public function factory($objectType = 'Description', $data = array())
    {
        $this->globalObject = $this->getRootNode();
        if($data){
            $this->globalObject = $this->getRootNode();
            foreach ($data as $key => $values){
                if(is_array ($values)){
                    $tree = $this->createObjects ('Result_'.$objectType, $this->globalObject, $values);
                }
            }
        }
        else{
            $tree = $this->createObjects ('Result_'.$objectType);
        }

        return $tree;
    }
    
    
}