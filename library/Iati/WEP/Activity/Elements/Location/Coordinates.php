<?php 
class Iati_WEP_Activity_Elements_Location_Coordinates extends Iati_WEP_Activity_Elements_Location
{
    protected $attributes = array('id', 'latitude', 'longitude', 'precision');
    protected $latitude;
    protected $longitude;
    protected $precision;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Coordinates';
    
    
    protected $validators = array(
                                'latitude' => array('NotEmpty', 'Float'),
                                'longitude' => array('NotEmpty', 'Float'),
                            );
                            
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'latitude' => array(
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
                'longitude' => array(
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
                
                'percision' => array(
                    'name' => 'percision',
                    'label' => 'Percision',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    protected $required = false;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        
        $this->options['percision'] = $model->getCodeArray('PercisionCode', null, '1');
        
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->latitude = (key_exists('@latitude', $data))?$data['@latitude']:$data['latitude'];
        $this->longitude = (key_exists('@longitude', $data))?$data['@longitude']:$data['longitude'];
        $this->percision = (key_exists('@percision', $data))?$data['@percision']:$data['percision'];
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
        $data['latitude'] = $this->latitude;
        $data['longitude'] = $this->longitude;
        $data['percision'] = $this->percision;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])){ continue; }
            
            if((in_array('NotEmpty', $this->validators[$key]) == true) && (empty($eachData)) && 
            (empty($this->required))) {  continue; }
            
            if((in_array('NotEmpty', $this->validators[$key]) == false) && (empty($eachData)))
            {  continue; }
            
            foreach($this->validators[$key] as $validator){
                $string = "Zend_Validate_". $validator;
              $validator = new $string();
              $error = '';
              if(!$validator->isValid($eachData)){
                $error = isset($this->error[$key])?array_merge($this->error[$key], $validator->getMessages())
                                :$validator->getMessages();
                  $this->error[$key] = $error;
                  $this->hasError = true;
  
              }  
            }
        }
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@latitude'] = $this->latitude;
        $data['@longitude'] = $this->longitude;
        $data['@percision'] = $this->percision;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
