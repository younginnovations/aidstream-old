<?php 
class Iati_WEP_Activity_Elements_Location_Administrative extends Iati_WEP_Activity_Elements_Location
{
    protected $attributes = array('id', 'text', 'xml_lang');
    protected $text;
    protected $country;
    protected $adm1;
    protected $adm2;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Administrative';
    
    
    protected $validators = array();
                            
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help location-administrative-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'country' => array(
                    'name' => 'country',
                    'label' => 'Country',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help location-administrative-country"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'adm1' => array(
                    'name' => 'adm1',
                    'label' => 'Admin-1',
                    'html' => '<textarea name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help location-administrative-adm1"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                
                'adm2' => array(
                    'name' => 'adm2',
                    'label' => 'Admin-2',
                    'html' => '<textarea name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help location-administrative-adm2"></div>',
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
        
        $this->options['country'] = $model->getCodeArray('Country', null, '1');
        $this->options['adm1'] = $model->getCodeArray('AdministrativeAreaCode(First-level)', null, '1');
        $this->options['adm2'] = $model->getCodeArray('AdministrativeAreaCode(Second-level)', null, '1');
        
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->country = (key_exists('@country', $data))?$data['@country']:$data['country'];
        $this->adm1 = (key_exists('@adm1', $data))?$data['@adm1']:$data['adm1'];
        $this->adm2 = (key_exists('@adm2', $data))?$data['@adm2']:$data['adm2'];
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
        $data['country'] = $this->country;
        $data['adm1'] = $this->adm1;
        $data['adm2'] = $this->adm2;
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
        $data['@country'] = $this->country;
        $data['@adm1'] = $this->adm1;
        $data['@adm2'] = $this->adm2;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/

    
}
