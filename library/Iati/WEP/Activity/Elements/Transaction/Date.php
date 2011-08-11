<?php 
class Iati_WEP_Activity_Elements_Transaction_Date extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'iso_date');
    protected $text;
    protected $iso_date;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Date';
    protected $validators = array(
                                'iso_date' => 'NotEmpty',
                            );
    
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
                'iso_date' => array(
                    'name' => 'iso_date',
                    'label' => 'Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'options' => '',
                    'attrs' => array('class' => array('form-text'))
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    
    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    }
    
    public function setOptions()
    {}
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->iso_date = (key_exists('@iso_date', $data))?$data['@iso_date']:$data['iso_date'];
        $this->text = $data['text'];
    }
        
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }
    public function validate()
    {
        $data['id'] = $this->id;
        $data['iso_date'] = $this->iso_date;
        $data['text'] = $this->text;
        
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])) continue;
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData)))  continue;
            
            $string = "Zend_Validate_". $this->validators[$key];
            $validator = new $string();
            
            if(!$validator->isValid($eachData)){
                
                $this->error[$key] = $validator->getMessages();
                $this->hasError = true;

            }
        }
    }
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['iso_date'] = $this->iso_date;
        $data['text'] = $this->text;
        
        return $data;
    }
}
