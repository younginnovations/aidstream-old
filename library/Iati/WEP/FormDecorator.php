<?php
class Iati_WEP_FormDecorator {
    
    private $_object;
    private $_parents = array();
    private $_html = array();
    //private $_oid;
    //private $_pid;
    
    public function __construct ($obj, $parents=array()) {
        $this->_object = $obj;
        $this->_parents = $parents;
    }
    
    /*
    public function setIds ($ids=array()) {
        if (empty($ids)) {
            $this->_oid = $this->_object->getObjectId();
//            var_dump($this->_oid);
            $this->_pid = (isset($this->_parent)) ?
                            $this->_parent->getObjectId() : 0;
        }
        else {
            $this->_oid = $ids['objectId'];
            $this->_pid = $ids['parentId'];
        }
    }
    */
    
    public function html ($label=true) {
        //$attributes = $this->_object->getAttributes();
        $htmlattrs = $this->_object->getHtmlAttrs();
        foreach ($htmlattrs as $attribute => $variables) {
            $html = '';
            $params = array();
            $id = isset($variables['attrs']['id']) ?
                                $variables['attrs']['id'] . '-' . $this->_oid : '';
            
            if ($label && isset($variables['label'])) {
                $html .= '<label for="'.$id.'">'.$variables['label'];
if($this->_object->getValidator($attribute)){  
if (in_array('NotEmpty', $this->_object->getValidator($attribute))) {
                    $html .= '<span class="required">*</span>';
                }
}
                $html .= '</label>';
            
            }
            
            $name = $this->_object->getClassName() . '_' . $variables['name'];            
            //$name .= ($this->_object->hasMultiple()) ?
            //            sprintf('[%s][%s]', $this->_pid, $this->_oid) : '';
            //print_r($this->_parents);
            //var_dump($this->_parents);
            foreach ($this->_parents as $par => $val) {
                //print_r($par);
                $_id = (string)((int)$val);
                $name .= ($_id != NULL) ? "[{$_id}]" : '';
                
            }
            
            //$name .= ($this->_object->hasMultiple()) ?
            //            sprintf("[%s]", $this->_object->getObjectId()) : '';
            
            $options = '';
            if (isset($variables['options'])) {
                $options = $this->makeOptions($this->_object->getAttr($attribute),
                                              $this->_object->getOptions($attribute));
            }
            
            $params = array(
                            'name' => $name,
                            'value' => ($this->_object->getAttr($attribute)) ? $this->_object->getAttr($attribute) : '',
                            'options' => $options,
                            'attrs' => $this->_attrs($variables['attrs'])
                            );
            $html .= sprintfn($variables['html'], $params);
            
            
            if ($this->_object->hasErrors() && $this->_object->getErrorMessage($attribute)) {
                $html .= '<p class="error">' . $this->_object->getErrorMessage($attribute) . '</p>';
            }
            
            array_push($this->_html, $html);
        }
        /*if($this->_object->hasMultiple()){
            $span = '<span class = "remove">Remove</span>';
            array_push($this->_html, $span);
        }*/
        return $this->_html;
    }
    
    public function wrap ($wrapper='p', $attrs=array()) {
        $html = '<%(tag)s ' . $this->_attrs($attrs) . '>%(this)s</%(tag)s>';
        $this->_html = sprintfn($html, array(
                            'tag' => $wrapper,
                            'this' => $this->_html
        ));
        return $this;
    }
    
    private function makeOptions ($attr, $options=array()) {
        if (empty($options)) {
            return false;
        }
        $optionHtml = '<option value="" label="Select anyone">Select anyone</option>';
        foreach ($options as $k => $v) {
            $sel = ($attr == $k) ? 'selected="selected"' : '';
            $optionHtml .= sprintf('<option value="%1s" %2s>%3s</option>', $k, $sel, $v);
//            print $optionHtml;exit;
        }
        return $optionHtml;
    }
    
    private function _attrs ($attrs=array()) {
        if (!empty($attrs)) {
            $_attrs = array();
            foreach ($attrs as $key=>$val) {
                
                if($key == 'id'){
                    $value = $value . '-' . $this->_object->getClassName() . '-' .
                                                                implode('', $this->_parents);
                }
                else{
                    $value = (is_array($val)) ? implode(' ', $val) : $val;
                }
                array_push($_attrs, $key . '="' . $value . '"');
            }
            //print_r($_attrs);
            return implode(' ', $_attrs);
        }
        return '';
    }
}

function sprintfn ($format, array $args = array()) {
    
    //var_dump($args);
    // map of argument names to their corresponding sprintf numeric argument value
    $arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);

    // find the next named argument. each search starts at the end of the previous replacement.
    for ($pos = 0; preg_match('/(?<=%)\(([0-9a-zA-Z_]\w*)\)/', $format, $match, PREG_OFFSET_CAPTURE, $pos);) {
        $arg_pos = $match[0][1];
        $arg_len = strlen($match[0][0]);
        $arg_key = $match[1][0];

        // programmer did not supply a value for the named argument found in the format string
        if (! array_key_exists($arg_key, $arg_nums)) {
            user_error("sprintfn(): Missing argument '${arg_key}'", E_USER_WARNING);
            return false;
        }

        // replace the named argument with the corresponding numeric one
        $format = substr_replace($format, $replace = $arg_nums[$arg_key] . '$', $arg_pos, $arg_len);
        $pos = $arg_pos + strlen($replace); // skip to end of replacement for next iteration
    }

    return vsprintf($format, array_values($args));
}

function sprintfn2 ($format, array $args = array()) {
    
    //$arg_nums = array_slice(array_flip(array_keys(array(0 => 0) + $args)), 1);
    
    foreach ($args as $key => $val) {
        
        if (preg_match("/%\($key\)s/", $format)) {
            
            $format = preg_replace("/%\($key\)s/", $val, $format);
            
        }
    }
    
    return $format;
}
