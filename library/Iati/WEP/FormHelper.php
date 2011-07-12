<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $globalObject;
    
    public function __construct($object) {
        
        $this->globalObject = $object;
        
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        //var_dump($registryTree->xml());exit;
        $this->objects = $registryTree->getChildNodes($this->globalObject);
//        print_r($this->objects);exit;
        
    }
    
    public function getForm() {
        if (empty($this->objects)) {
            throw new Exception("Nothing to do with empty object list");
        }
        
        $form = '';
        $form .= $this->globalObject->toHtml();
        foreach ($this->objects as $obj) {
//            print_r($obj);//exit;
            $error_code = $obj->hasErrors();
            $form .= $obj->toHtml($error_code);
        }
        
        $formArray = array();
//        $this->getChildForm($this->objects, $formArray);

//        $form .= implode("", $formArray);
        $form_string = $this->_form($this->globalObject->getObjectName(), '#');
        
        $form = sprintf($form_string, $form);
        
        return $this->_wrap($form, 'div');
    }
    
    public function getChildForm($object, &$formArray)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        foreach ($object as $obj) {
            /*$error_code = $obj->hasErrors();
            $formArray[] = $obj->toHtml($error_code);*/
            if($registryTree->getChildNodes($obj) != null){
                $obj = $registryTree->getChildNodes($obj);
                $this->getChildForm($obj, $formArray);
            }
            else{
               $error_code = $obj->hasErrors();
               $formArray[] = $obj->toHtml($error_code); 
            }
            
        }
    }
    
    
    private function _form($name, $action, $method="post", $attribs=null) {
        $_form = sprintf('<fieldset><legend>%s</legend><form id = "element-form" name="%s" action="%s" method="%s" %s>',
                         $name,$name, $action, $method, $this->_attr($attribs));
        
        $_form .= '<div id = "form-elements-wrapper">%s</div>';
        if ($this->globalObject->hasMultiple()) {
            $_form .= $this->_addMore(array('id'=>'add-more'));
        }
        $_form .= '<input type="submit" id="Submit" value="Save" />';
        $_form .= '</form></fieldset>';
        return $_form;
    }
    
    protected function _wrap($formElement, $tag='p', $attribs=null) {
        return sprintf('<%s %s>%s</%s>', $tag,
                       $this->_attr($attribs), $formElement, $tag);
    }
    
    protected function _addMore($attribs=null, $tag='div', $text='Add More') {
        return sprintf('<div %s>%s</div>', $this->_attr($attribs), $text);
    }
    
    protected function _attr($attribs) {
        $_attrs = array();
        if ($attribs) {
            foreach ($attribs as $key=>$value) {
                array_push($_attrs, $key.'="'.$value.'"');
            }
        }
        return (count($_attrs) > 0 ? implode(' ', $_attrs) : '');
    }
}
?>