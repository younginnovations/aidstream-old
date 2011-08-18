<?php 
class Iati_WEP_Activity_Elements_Budget extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'type');
    protected $type;
    protected $id = 0;
    protected $options = array();
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
                    'label' => 'Budget Type',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                    )
    );
    protected $className = 'Budget';
    
    protected $validators = array(
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
        $this->setOptions();
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->type = (isset($data['@type']))?$data['@type']:$data['type'];
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['type'] = $model->getCodeArray('BudgetType', null, '1');
    }
    
    public function getOptions($attr)
    {
        return $this->options[$attr];
    }

    public function isRequired()
    {
        return $this->required;
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
        
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@type'] = $this->type;
        
        return $data;
    }
}
