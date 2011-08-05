<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $registryTree;
    
    public function __construct () {
        
        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();
        
    }
    
    public function getSubForm($parents=array()) {
        $form = '';
        
        $formArray = '';
        
        $obj = $this->registryTree->getRootNode();
        
        $decorate = new Iati_WEP_FormDecorator($obj, $parents);
        $decoratedHtml = $decorate->html();
        $formArray .= '<fieldset><legend>'.$obj->getClassName().'</legend>';
        
        foreach($decoratedHtml as $eachHtml){
             $formArray .= "<p> $eachHtml </p>";
        }
        
        if ($obj->hasMultiple()) {
             $fc = Zend_Controller_Front::getInstance();
            $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
//            return sprintf('<a href=%s %s>%s</a>', $_SERVER[''], $this->_attr($attribs), $text);
            $formArray .= $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
        }
        
        
        
        
        /*if($obj->hasMultiple()){
            $fornArray[] = '<span class = "remove">Remove</span>';
        }*/
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            
            foreach ($this->registryTree->getChildNodes($obj) as $child) {
                $this->getChildForm2($child, $formArray);
            }
            
        }
        
        $formArray .= '</fieldset>';
        

        //$form .= implode("", $formArray);
        //$form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        
        //$form = sprintf($form_string, $form);
        
        return $this->_wrap($formArray, 'div');
    }
    
    public function getForm() {
        /*if (empty($this->objects)) {
            throw new Exception("Nothing to do with empty object list");
        }
        */
        $form = '';
        
        $formArray = array();
        $this->getChildForm($this->registryTree->getRootNode(), $formArray);

        $form .= implode("", $formArray);
        $form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        
        $form = sprintf($form_string, $form);
        
        return $this->_wrap($form, 'div');
    }
    
    public function getChildForm($obj, &$formArray)
    {
        $decorate = new Iati_WEP_FormDecorator($obj,
                                    $this->registryTree->getParents($obj));
        $decoratedHtml = $decorate->html();
        $formArray[] = '<fieldset><legend>'.$obj->getClassName().'</legend>';
        
        foreach($decoratedHtml as $eachHtml){
             $formArray[] = "<p> $eachHtml </p>";
        }
        
        if ($obj->hasMultiple()) {
             $fc = Zend_Controller_Front::getInstance();
            $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
//            return sprintf('<a href=%s %s>%s</a>', $_SERVER[''], $this->_attr($attribs), $text);
            $formArray[] = $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
        }
        $formArray[] = '</fieldset>';
        
        
        
        /*if($obj->hasMultiple()){
            $fornArray[] = '<span class = "remove">Remove</span>';
        }*/
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            
            foreach ($this->registryTree->getChildNodes($obj) as $child) {
                $this->getChildForm($child, $formArray);
            }
            
        }
        
    }
    
    public function getForm2() {
        /*if (empty($this->objects)) {
            throw new Exception("Nothing to do with empty object list");
        }
        */
        $form = '';
        
        //$formArray = array();
        $this->getChildForm2($this->registryTree->getRootNode(), $form);

        //$form .= implode("", $formArray);
        $form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        
        if ($this->registryTree->getRootNode()->hasMultiple()) {
                    $fc = Zend_Controller_Front::getInstance();
                    $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
                    // return sprintf('<a href=%s %s>%s</a>', $_SERVER[''], $this->_attr($attribs), $text);
                    $form .= $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
                }
        $form = sprintf($form_string, $form);
        
        return $this->_wrap($form, 'div');
    }
    
    public function getChildForm2($obj, &$form)
    {
        $decorate = new Iati_WEP_FormDecorator($obj,
                                    $this->registryTree->getParents($obj));
        $decoratedHtml = $decorate->html();
        $form .= '<fieldset><legend>'.$obj->getClassName().'</legend>';
        
        foreach($decoratedHtml as $eachHtml){
             $form .= "<p> $eachHtml </p>";
        }
        
        /*if($obj->hasMultiple()){
            $fornArray[] = '<span class = "remove">Remove</span>';
        }*/
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            
            foreach ($this->registryTree->getChildNodes($obj) as $key => $child) {
                $this->getChildForm2($child, $form);
                
                if (($key == (sizeof($obj) - 1)) && $obj->hasMultiple()) {
                    $fc = Zend_Controller_Front::getInstance();
                    $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
                    // return sprintf('<a href=%s %s>%s</a>', $_SERVER[''], $this->_attr($attribs), $text);
                    $form .= $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
                }
            }
            
        }
        
        $form .= '</fieldset>';
        //return $form;
        
    }
   /* $fc = Zend_Controller_Front::getInstance();
        $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node?class=' ;
        return sprintf('<a href=%s %s>%s</a>', $_SERVER[''], $this->_attr($attribs), $text);*/
    
    
    private function _form($name, $action, $method="post", $attribs=null) {
        $_form = sprintf('<fieldset><legend>%s</legend><form id = "element-form" name="%s" action="%s" method="%s" %s>',
                         $name,$name, $action, $method, $this->_attr($attribs));
        
        $_form .= '<div id = "form-elements-wrapper">%s</div>';
        /*if ($this->registryTree->getRootNode()->hasMultiple()) {
            $_form .= $this->_addMore(array('id'=>'add-more'));
        }*/
        
        $_form .= '<input type="submit" id="Submit" value="Save" />';
        $_form .= '</form></fieldset>';
        return $_form;
    }
    
    protected function _wrap($formElement, $tag='p', $attribs=null) {
        return sprintf('<%s %s>%s</%s>', $tag,
                       $this->_attr($attribs), $formElement, $tag);
    }
    
    protected function _addMore($attribs=null, $tag='div', $text='Add More') {
        $text = '<' . $tag . ' ' . $this->_attr($attribs) . '>' . $text . '</' . $tag . '>'; 
        return sprintf('<div class="addmore">%s</div>', $text);
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