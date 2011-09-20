<?php 
class Iati_WEP_Activity_Elements_PlannedDisbursement extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'updated');
    protected $updated;
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
                    'updated' => array(
                    'name' => 'updated',
                    'label' => 'Updated Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => 'datepicker form-text', 'id' => 'updated')
                
                ),
    );
    protected $className = 'PlannedDisbursement';
    
    protected $validators = array();
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
        $this->updated = (isset($data['@updated']))?$data['@updated']:$data['updated'];
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
        
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@updated'] = $this->updated;
        
        return $data;
    }
}
