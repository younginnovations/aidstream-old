<?php 
class Iati_WEP_Activity_Elements_Location extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'percentage');
    protected $percentage;
    protected $id = 0;
    protected $options = array();
    protected $validator = array(
                                'percentage' => array('Int')
                            );
    protected $attributes_html = array(
                    'id' => array(
                    'name' => 'id',
                    'label' => '',
                    'html' => '<input type="hidden" name="%(name)s" value="%(value)s" />',
                    ),
                    'percentage' => array(
                    'name' => 'percentage',
                    'label' => 'Percentage',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                
                ),
    );
    protected $className = 'Location';
    
    protected $validators = array(
                                'percentage' => 'Int',
                            );
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = true;
    
    

    public function __construct()
    {
        parent :: __construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->multiple = true;
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->percentage = (isset($data['@percentage']))?$data['@percentage']:$data['percentage'];
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
    
    public function getValidator()
    {
        return $this->validators;
    }
    
    public function validate(){
        $data['id'] = $this->id;
        $data['percentage'] = $this->percentage;
        
        parent :: validate($data);
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@percentage'] = $this->percentage;
        
        return $data;
    }
}
