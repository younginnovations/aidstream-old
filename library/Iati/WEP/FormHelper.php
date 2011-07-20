<?php

class Iati_WEP_FormHelper {
    
    protected $objects = array();
    protected $registryTree;
    
    public function __construct () {
        
        $this->registryTree = Iati_WEP_TreeRegistry::getInstance();
        
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
                                    $this->registryTree->getParentNode($obj));
        $decoratedHtml = $decorate->html();
        
        $formArray[] = '<fieldset>';
        foreach($decoratedHtml as $eachHtml){
             $formArray[] = "<p> $eachHtml </p>";
        }
        $formArray[] = '</fieldset>';
        
        if ($this->registryTree->getChildNodes($obj) != NULL) {
            
            foreach ($this->registryTree->getChildNodes($obj) as $child) {
                $this->getChildForm($child, $formArray);
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