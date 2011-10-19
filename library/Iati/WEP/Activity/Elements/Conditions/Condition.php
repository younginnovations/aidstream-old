<?php
class Iati_WEP_Activity_Elements_Conditions_Condition extends 
                                    Iati_WEP_Activity_Elements_Conditions
{
    protected $attributes = array('id', 'text', 'type', 'xml_lang');
    protected $text;
    protected $type;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Condition';
    protected $validators = array(
                                
                            );
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Description',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help conditions-condition-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'type' => array(
                    'name' => 'type',
                    'label' => 'Condition Type',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help conditions-condition-type"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help conditions-condition-xml_lang"></div>',
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
        $this->options['type'] = $model->getCodeArray('ConditionType', null, '1');
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {
        //print_r($data);exit;
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->text = $data['text'];
        //print_r($this->xml_lang);exit;
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
        $data['text'] = $this->text;
        $data['type'] = $this->type;
        $data['xml_lang'] = $this->xml_lang;
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
        $data['text'] = $this->text;
        $data['@type'] = $this->type;
        $data['@xml_lang'] = $this->xml_lang;
        return $data;
    }
    
    
}
