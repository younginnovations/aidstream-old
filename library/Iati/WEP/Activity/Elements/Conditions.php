<?php
class Iati_WEP_Activity_Elements_Conditions extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'attached');
    protected $validAttribs = array('@attached');
    protected $id = 0;
    protected $attached;
    protected $options = array();
    protected $multiple = true;
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
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
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
        $this->multiple = true;
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $this->options['attached'] = array('No' => '0', 'Yes' => '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->attached = (key_exists('@attached', $data))?$data['@attached']:$data['attached'];
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
        return $data;
    }
}