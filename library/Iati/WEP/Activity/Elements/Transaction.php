<?php 
class Iati_WEP_Activity_Elements_Transaction extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('transaction_id');
    protected $options = array();
    protected $multiple = true;
    
    protected $attributes_html = array(
                'transaction_id' => array(
                    'name' => 'transaction_id',
                    'label' => '',
                    'html' => '<input type="hidden" name="%(name)s" %(value)s />',
//                    'attrs' => array('id' => 'id')
                ),
    );
    
    protected static $count = 0;
    protected $objectId;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
//        $this->setOptions();
    }
    
    
    
    public function setAttributes () {
        
    }
    
    public function getClassName () {
        return 'Transaction';
    }
    
    public function getAttr ($attr) {
        $vars = get_object_vars($this);
        if (in_array($attr, array_keys($vars))) {
            if (isset($vars[$attr])) {
                return $vars[$attr];
            }
        }
        return false;
    }
    
    public function getAttributes () {
        return $this->attributes;
    }
    
    public function getHtmlAttrs() {
        return $this->attributes_html;
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
}