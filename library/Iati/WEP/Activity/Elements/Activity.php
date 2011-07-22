<?php
class Iati_WEP_Activity_Elements_Activity extends Iati_WEP_Activity_Elements_ElementBase
{
protected $attributes = array('activity_id');
    protected $activity_id;
    protected $options = array();
    
    protected $attributes_html = array(
                'activity_id' => array(
                    'name' => 'activity_id',
//                    'label' => 'Text',
                    'html' => '<input type="hidden" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
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
    
    
    public function setOptions()
    {
//        $model = new Model_Wep();
//        $this->options['code'] = $model->getCodeArray('TransactionTypeCode', null, '1');
    }
    
    public function getClassName () {
        return 'Activity';
    }
    
    public function setAttributes ($data) {
        $this->activity_id = $data['activity_id'];
    }
public function getAttributes () {
        return $this->attributes;
    }
public function getHtmlAttrs()
    {
        return $this->attributes_html;
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
    
    public function getObjectId()
    {
//        print_r()
        return $this->objectId;
    }
}