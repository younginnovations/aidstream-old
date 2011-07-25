<?php

class Iati_WEP_Activity_DbWrapper {
    
    private $object;
    private $primary_key = 0;
    private $foreign_key = 0;
    
    public function __construct ($obj) {
        $this->object = $obj;
    }
    
    public function getClassName () {
        return $this->object->getClassName();
    }
    
    public function setPrimary ($val) {
        $this->primary_key = $val;
    }
    
    public function getPrimary () {
        return $this->primary_key;
    }
    
    public function __call ($name, $arguments) {
        return call_user_func_array(array($this->object, $name), $arguments);
    }
}