<?php
class Iati_WEP_Activity_Elements_Conditions extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'attached');
    protected $id = 0;
    protected $attached;
    protected $options = array();
    protected $multiple = false;
    protected $attributes_html = array(
                    'id' => array(
                    'name' => 'id',
                    'label' => '',
                    'html' => '<input type="hidden" name="%(name)s" value="%(value)s" />',
                ),
                //'attached' => array(
                //    'name' => 'attached',
                //    'label' => 'Conditions Attached',
                //    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                //    'options' => '',
                //    'attrs' => array('class' => array('form-select'))
                //),
                
                'attached' => array(
                    'name' => 'attached',
                    'label' => 'Conditions Attached',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help conditions_attached"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
    );
    protected $className = 'Conditions';
    
    protected $validators = array(
                                  'attached' => array('NotEmpty'),
                                  );
    protected static $count = 0;
    protected $objectId;
    protected $error = array(); 
    protected $hasError = false;
    
    public function __construct()
    {
        parent :: __construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $this->options['attached'] = array('0' => 'No', '1' => 'Yes');
    }
    
    public function setAttributes ($data) {
        //print_r($data);exit;
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->attached = (key_exists('@attached', $data))?$data['@attached']:$data['attached'];
        //print_r($this->attached);exit;
        //print_r($data);exit;
    }
    
    public function getOptions($attr)
    {
        return $this->options[$attr];
    }
    
    public function getAttributes () {
        return $this->attributes;
    }
    public function getClassName ()
    {
        return $this->className;
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }
    
    public function validate(){
        $data['id'] = $this->id;
        $data['attached'] = $this->attached;
        //print_r($data);exit;
        parent :: validate($data);
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@attached'] = $this->attached;
        //print_r($data);exit;
        return $data;
    }
}