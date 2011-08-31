<?php 
class Iati_WEP_Activity_Elements_Result extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'type');
    protected $validAttribs = array( '@type');
    protected $type;
    protected $id = 0;
    protected $options = array();
    protected $multiple = true;
//    protected $validator = array(protected $hasError = false;
//                                '' 
//                            );
    protected $attributes_html = array(
                    'id' => array(
                    'name' => 'id',
                    'label' => '',
                    'html' => '<input type="hidden" name="%(name)s" value="%(value)s" />',
                    ),
                    'type' => array(
                    'name' => 'type',
                    'label' => 'Result Type',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                    ),
                
    );
    protected $className = 'Result';
    
    protected $validators = array(
                                'type' => array('NotEmpty'),
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
        $model = new Model_Wep();
        $this->options['type'] = $model->getCodeArray('ResultType', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->ref = (isset($data['@type']))?$data['@type']:$data['type'];
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
        $data['type'] = $this->type;
        
        parent::validate($data);
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['type'] = $this->type;
        
        return $data;
    }
}
