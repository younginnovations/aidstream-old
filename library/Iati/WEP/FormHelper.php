<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $registryTree;
    protected $ajaxCall;
    
    public function __construct () {
        
        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();
        
        $fc = Zend_Controller_Front::getInstance();
        $this->url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
        
    }
    
    public function getFormWithAjax ($ajax=true) {
        $this->ajaxCall = $ajax;
    }
    
    public function getForm() {
        
        $form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        
        //$form = implode('', $this->genForm());
        $root = $this->registryTree->getRootNode();
        $finalHtml = $this->genHtml(array($root)); 
        
        $this->generateForm($root, $finalHtml);
        //$form = $this->genForm();
        $form = sprintf($form_string, $finalHtml);
        
        //$this->genForm();
        //return $this->_wrap(implode('', ), 'div');
        
        return $this->_wrap($form, 'div');
    }
    
    public function genHtml ($nodes) {
        
        $html = array();
        
        $finalNodes = $this->groupElements($nodes);
        
        foreach ($finalNodes as $ele) {
            $_ht = array();
            foreach($ele as $key => $obj) {
                
                $_ht[] = $this->myForm($obj);
                
                $addMoreCond = ($obj == $this->registryTree->getRootNode()
                                && $this->ajaxCall) ? false : true;
                
                if ($key == (sizeof($ele) - 1) && $obj->hasMultiple() && $addMoreCond) {
                    $parents = $this->registryTree->getParents($obj);
                    $url = $this->url;
                    $urlParts = array();
                    foreach ($parents as $key => $par) {
                        $urlParts[$key] = 'parent' . $key . '=' .
                                                        $par->getClassName();
                    }
                    $url .= '?' . implode('&', $urlParts) . '&classname=' .
                                $obj->getClassName();
                    $_ht[] =
                               $this->_addMore(array("href" => $url), "a");
                }
            }
            $_html = implode('', $_ht);
            $html[] = (sizeof($_ht) > 1) ?
                                sprintf('<div>%s</div>', $_html) : $_html;
        }
        $htmls = (sizeof($html) > 1) ?
                                sprintf('<div>%s</div>', implode('', $html)) :
                                implode('', $html);
        return $htmls;
    }
    
    public function groupElements ($nodes) {
        $return = array();
        foreach ($nodes as $ele) {
            if (array_key_exists($ele->getClassName(), $return)) {
                array_push($return[$ele->getClassName()], $ele);
            }
            else {
                $return[$ele->getClassName()] = array($ele);
            }
        }
        return $return;
    }
    
    public function myForm($obj)
    {
        $form = "";
        $decorate = new Iati_WEP_FormDecorator($obj,
                                    $this->registryTree->getParents($obj));
        $decoratedHtml = $decorate->html();
        
        $form .= sprintf("<div class=\"%s\">", $obj->getClassName());
        
        foreach($decoratedHtml as $eachHtml){
             $form .= "<p> $eachHtml </p>";
        }
        
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            $form .= "<div>%(".(string)$this->registryTree->getNodeId($obj).")s</div>";
            //$form .= "<div>%s</div>";
        }
        $form .= "</div>";
        return $form;
        
    }
    
    public function generateForm ($root, &$result='') {
        $childs = $this->registryTree->getChildNodes($root);
        
        if ($childs != NULL) {
            
            $parentId = $this->registryTree->getNodeId($root);
            $result = sprintfn($result,
                               array($parentId => $this->genHtml($childs)));
            foreach($childs as $obj) {
                $this->generateForm($obj, $result);
            }
        }
    }
    
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