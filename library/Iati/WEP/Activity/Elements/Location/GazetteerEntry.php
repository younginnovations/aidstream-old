<?php 
class Iati_WEP_Activity_Elements_Location_GazetteerEntry extends Iati_WEP_Activity_Elements_Location
{
    protected $attributes = array('id', 'gazetteer_ref', 'text');
    protected $gazetteer_ref;
    protected $text;
    protected $id = 0;
    protected $options = array();
    protected $className = 'GazetteerEntry';
    
    
    protected $validators = array(
                                'gazetteer_ref' => array('NotEmpty'),
                                'text' => array('NotEmpty'),
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
                'gazetteer_ref' => array(
                    'name' => 'gazetteer_ref',
                    'label' => 'Gazetteer Agency',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = true;
    protected $required = false;
    protected $isAttributeSet = false;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        
        $this->options['gazetteer_ref'] = $model->getCodeArray('GazetteerAgency', null, '1');
         
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->gazetteer_ref = (key_exists('@gazetteer_ref', $data))?$data['@gazetteer_ref']:$data['gazetteer_ref'];

        $this->text = $data['text'];
        $this->attributeState();
    }
    
    public function attributeState()
    {
        foreach($this->attributes as $attribute){
            if($this->$attribute){
                $this->isAttributeSet = true;
                break;
            }
        }
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
        $data['gazetteer_ref'] = $this->gazetteer_ref;
        $data['text'] = $this->text;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])){ continue; }
            
            if($this->required){
                if((in_array('NotEmpty', $this->validators[$key]) == false) && (empty($eachData))){
                    continue;
                }
                
            }else{
                if(!$this->isAttributeSet){
                    continue;
                }else{
                    if((in_array('NotEmpty', $this->validators[$key]) == false) && (empty($eachData))){
                        continue;
                    }
                }
            }
            
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
        $data ['id'] = $this->id;
        $data['@gazetteer_ref'] = $this->gazetteer_ref;
        $data['text'] = $this->text;
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
