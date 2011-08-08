<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $registryTree;
    
    public function __construct () {
        
        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();
        
        $fc = Zend_Controller_Front::getInstance();
        $this->url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
        
        
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
        //$form = '';
        
        //$formArray = array();
        //$this->getChildForm($this->registryTree->getRootNode(), $formArray);

        //$form .= implode("", $formArray);
        $form_string = $this->_form($this->registryTree->getRootNode()->getClassName(), '#');
        
        //$form = implode('', $this->genForm());
        $form = $this->genForm();
        $form = sprintf($form_string, $form);
        
        //$this->genForm();
        //return $this->_wrap(implode('', ), 'div');
        
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
    /*
    public function getForm2() {
        $form = '<div>';
        $root = $this->registryTree->getRootNode();
        
        $fc = Zend_Controller_Front::getInstance();
        $url = 'http://' . $_SERVER['HTTP_HOST'].$fc->getBaseUrl().'/wep/clone-node' ;
        //print_r($root);
        //print_r($this->registryTree->getChildNodes($root));
        if ($this->registryTree->getChildNodes($root) != NULL) {
            $childs = $this->registryTree->getChildNodes($root);
            
            foreach ($childs as $key => $ch) {
                $form .= '<div>';
                $form .= $this->getChildForm2($ch);
                
                if ($this->registryTree->getChildNodes($ch) != NULL) {
                    $childsInner = $this->registryTree->getChildNodes($ch);
            
                    foreach ($childsInner as $k => $chi) {
                        $form .= '<div>';
                        $form .= $this->getChildForm2($chi);
                        //print_r($form);
                        
                        if ($this->registryTree->getChildNodes($chi) != NULL) {
                            $childs2 = $this->registryTree->getChildNodes($chi);
            
                            $form .= $this->getInnerForm($childs2);
                        }
                        
                        if ($chi->hasMultiple()) {
                            $form .= $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
                        }
                        $form .= '</div>';
                    }
                }
                
                //print_r($form);
                if (($key == (sizeof($childs) - 1)) && $ch->hasMultiple()) {
                    $form .= $this->_addMore(
                                           array('href' => $url), 'a'
                                           );
                }
                $form .= '</div>';
            }
        }
        $form .= '</div>';
        return $form;
    }
    
    public function getChildForm2($obj)
    {
        $decorate = new Iati_WEP_FormDecorator($obj,
                                    $this->registryTree->getParents($obj));
        $decoratedHtml = $decorate->html();
        $form .= '<fieldset><legend>'.$obj->getClassName().'</legend>';
        
        foreach($decoratedHtml as $eachHtml){
             $form .= "<p> $eachHtml </p>";
        }
        
        $form .= '</fieldset>';
        return $form;
        
    }
    
    public function getInnerForm ($child) {
        $form = '';
        foreach ($child as $key => $ch) {
            $form .= '<div>';
            $form .= $this->getChildForm2($ch);
            //print_r($form);
            if ($ch->hasMultiple()) {
                $form .= $this->_addMore(
                            array('href' => $url), 'a'
                                );
            }
            $form .= '</div>';
        }
        return $form;
    }
    */
    function genForm () {
        
        $mappedArray = array();
        $root = $this->registryTree->getRootNode();
        $this->getAllGroundElements($root, $mappedArray);
        
        //print_r($mappedArray);
        //print_r(sizeof($mappedArray));
        $parents = array();
        
        foreach($mappedArray as $ele) {
            
            if (!empty($ele)) {
                $parentId = $this->registryTree->getNodeId($this->registryTree->getParentNode($ele));
            
                if (array_key_exists($parentId, $parents)) {
                    array_push($parents[$parentId], $ele);
                }
                else {
                    $parents[$parentId] = array($ele);
                }
            }
            
        }
        
        $return = $this->genFullForm($parents);
        
        
        //print_r($return);
        
        foreach ($return as $key => $val) {
            $obj = $this->registryTree->getNodeById($key);
            $par = $this->registryTree->getParentNode($obj);
            if ($par != NULL) {
                $parId = $this->registryTree->getNodeId($par);
                if (array_key_exists($parId, $return)) {
                    $return[$parId] = sprintf($return[$parId],
                                               $return[$key]);
                    unset($return[$key]);
                }
            }
        }
        
        $return[$this->registryTree->getNodeId($root)] = sprintf(
                                        $this->genHtml(array($root)), array_pop($return));
        
        return $return[$this->registryTree->getNodeId($root)];
        
    }
    
    function normalise(&$return, &$round=1) {
        foreach ($return as $key => $val) {
            $obj = $this->registryTree->getNodeById($key);
            $par = $this->registryTree->getParentNode($obj);
            if ($par != NULL) {
                $parId = $this->registryTree->getNodeId($par);
                if (array_key_exists($parId, $return)) {
                    $return[$parId] = sprintf($return[$parId],
                                               $return[$key]);
                    //unset($return[$key]);
                }
            }
        }
        if ($round > sizeof($return)) {
            return $return;
        }
        else {
            $this->normalise($return, ++$round);
        }
    }
    
    function genFullForm (&$parents, &$final=array(), &$level=1) {
        foreach($parents as $key => $items) {
            $final[$key] = "<div>".$this->genHtml($items)."</div>";
            
            unset($parents[$key]);
            $curObj = $this->registryTree->getNodeById($key);
            $parentNode = $this->registryTree->getParentNode($curObj);
            if ($parentNode != NULL) {
                $parentNodeId = $this->registryTree->getNodeId($parentNode);
                if (array_key_exists($parentNodeId, $parents)) {
                    array_push($parents[$parentNodeId], $curObj);
                }
                else {
                    $parents[$parentNodeId] = array($curObj);
                }
            }
        }
        
        if ($level > 3) {
            return $final;
        }
        else{
            $this->genFullForm($parents, $final, ++$level);
        }
        
        
        
        
        /*
        if (sizeof($parents) > 1) {
            foreach ($parents as $key => $item) {
                $classname = $this->registryTree->getParentNode($item[0])->getClassName();
                $parentEle = $this->registryTree->getParentById($classname, $key);
            
                if (array_key_exists($parentEle, $parents)) {
                    $final[$parentEle] = $final[$parentEle] . $final[$key];
                    unset($final[$key]);
                }
            }
            if (sizeof($final) > 1) {
                foreach ($final as $key => $val) {
                    $obj = $this->registryTree->getNodeById($key);
                    $parentEle = $this->registryTree->getParentById($obj->getClassName(), $key);
                    if (array_key_exists($parentEle, $final)) {
                        $final[$parentEle] .= $val;
                    }
                    else {
                        $final[$parentEle] = implode($this->genHtml($obj)) . $val;
                    }
                }
            }
            else {
                return $final;
            }
            
        }
        */
        return $final;
    }
    
    function genHtml ($nodes) {
        $htmls = "<div>";
        
        $finalNodes = $this->groupElements($nodes);
        
        foreach ($finalNodes as $ele) {
            $htmls .= "<div>";
            foreach($ele as $key => $obj) {
                $htmls .= $this->myForm($obj);
                
                if ($key == (sizeof($ele) - 1) && $obj->hasMultiple()) {
                    $htmls .=
                               $this->_addMore(array("href" => $this->url), "a");
                }
            }
            $htmls .= "</div>";
        }
        $htmls .= "</div>";
        return $htmls;
    }
    
    function groupElements ($nodes) {
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
    
    function myForm($obj)
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
            //$form .= "<div>%(".$this->registryTree->getNodeId($obj).")s</div>";
            $form .= "<div>%s</div>";
        }
        $form .= "</div>";
        return $form;
        
    }
    
    function getAllGroundElements ($rootNode, &$elements) {
        $childs = $this->registryTree->getChildNodes($rootNode);
        
        if ($childs != NULL) {
            foreach($childs as $obj) {
                array_push($elements, $this->getAllGroundElements($obj, $elements));
            }
        }
        else {
            return $rootNode;
        }
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