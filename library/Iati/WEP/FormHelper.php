<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $registryTree;
    protected $ajaxCall = false;
    protected $indexValues;
    protected $parentNames;
    
    public function __construct () {
        
        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();
        
        $fc = Zend_Controller_Front::getInstance();
        $this->baseurl = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl();
        
    }
    
    public function getFormWithAjax ($parents, $items) {
        $this->ajaxCall = true;
        $this->parentNames = $parents;
        $this->indexValues = $items;
        $this->incrementIndex();
        
        $root = $this->registryTree->getRootNode();
        $finalHtml = $this->genHtml(array($root)); 
        
        $this->generateForm($root, $finalHtml);
        
        return $finalHtml;
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
        
        return $form;
    }
    
    public function genHtml ($nodes) {
        
        $html = array();
        
        $finalNodes = $this->groupElements($nodes);
        
        foreach ($finalNodes as $ele) {
            $_ht = array();
            if (!$this->ajaxCall) {
                $_ht[] = sprintf('<h2 class="form-title">%s</h2>', $ele[0]->getClassName());
            }
            foreach($ele as $key => $obj) {
                
                $_ht[] = $this->myForm($obj);
                
                $addMoreCond = ($obj == $this->registryTree->getRootNode()
                                && $this->ajaxCall) ? false : true;
                
                if ($key == (sizeof($ele) - 1) && $obj->hasMultiple() && $addMoreCond) {
                    
                    $url = $this->getUrl($obj, '/wep/clone-node');
                    
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
        $decorate = new Iati_WEP_FormDecorator($obj, $this->getIndexValues($obj));
        $decoratedHtml = $decorate->html();
        
        $form .= sprintf("<div class=\"%s\">", $obj->getClassName());
        
        foreach($decoratedHtml as $eachHtml){
             $form .= "<div class='form-item'> $eachHtml </div>";
        }
        
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            $form .= "%(".(string)$this->registryTree->getNodeId($obj).")s";
            //$form .= "<div>%s</div>";
        }
        
        if ($obj->hasMultiple()) {
            $url = $this->getUrl($obj, '/wep/remove-elements');
            $form .= sprintf('<span class="remove"><a href="%s">Remove</a></span>',
                         $url);
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
    
    public function getIndexValues ($object) {
        if ($this->ajaxCall) {
            return $this->indexValues;
        }
        else {
            $arr = array();
            foreach ($this->registryTree->getParents($object) as $parObj) {
                if ($parObj->hasMultiple()) {
                    array_push($arr, $parObj->getObjectId());
                }
            }
            if ($object->hasMultiple()) {
                array_push($arr, $object->getObjectId());
            }
            
            return $arr;
        }
    }
    
    private function getUrl($obj, $urlPath) {
        $parents = array();
        //print_r($parents);
        if ($this->ajaxCall) {
            $parents = $this->parentNames;
        }
        $parentObjects = $this->registryTree->getParents($obj);
        foreach ($parentObjects as $par) {
            $parName = $par->getClassName();
            if ($this->ajaxCall == false && $par != $this->registryTree->getRootNode()) {
                if (!in_array($parName, $parents)) {
                    array_push($parents, $parName);
                }
            }
        }
        
        print_r($parents);
        $url = $this->baseurl . $urlPath;
        $urlParts = array();
        foreach ($parents as $key => $par) {
            $urlParts[$key] = 'parent' . $key . '=' . $par;
        }
        array_push($urlParts, 'classname=' . $obj->getClassName());
        $url .= '?' . implode('&', $urlParts);
        
        return $url;
    }
    
    private function incrementIndex () {
        $current = (int)array_pop($this->indexValues) + 1;
        array_push($this->indexValues, $current);
    }
    
    private function _form($name, $action, $method="post", $attribs=null) {
        $_form = sprintf('<form id="element-form" name="%s" action="%s" method="%s" %s>',
                         $name, $action, $method, $this->_attr($attribs));
        
        $_form .= '<div id = "form-elements-wrapper">%s</div>';
        /*if ($this->registryTree->getRootNode()->hasMultiple()) {
            $_form .= $this->_addMore(array('id'=>'add-more'));
        }*/
        
        $_form .= '<input type="submit" id="Submit" value="Save" class="form-submit"/>';
        $_form .= '</form>';
        return $_form;
    }
    
    protected function _wrap($formElement, $tag='p', $attribs=null) {
        return sprintf('<%s %s>%s</%s>', $tag,
                       $this->_attr($attribs), $formElement, $tag);
    }
    
    protected function _addMore($attribs=null, $tag='div', $text='Add More') {
        $text = '<' . $tag . ' ' . $this->_attr($attribs) . '>' . $text . '</' . $tag . '>'; 
        return sprintf('<span class="addmore">%s</span>', $text);
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
