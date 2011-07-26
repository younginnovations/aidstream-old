<?php
class Iati_WEP_Activity_Elements_Activity extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('activity_id');
    protected $activity_id;
    protected $options = array();
    protected $validators = array(
                                'activity_id' => 'NotEmpty',
    );

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
    protected $hasError = false;

    protected $errors = array();

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    }


    public function setOptions()
    {
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

    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }

    public function getObjectId()
    {
        return $this->objectId;
    }

    public function validate()
    {
        $data['activity_id'] = $this->activity_id;
        
        parent :: validate($data);
        
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->activity_id;
        
        return $data;
    }
}