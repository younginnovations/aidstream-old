<?php 
class Iati_WEP_Activity_Elements_Budget_PeriodStart extends Iati_WEP_Activity_Elements_Budget
{
    protected $attributes = array('id', 'text', 'iso_date');
    protected $text;
    protected $iso_date;
    protected $id = 0;
    protected $options = array();
    protected $className = 'PeriodStart';
    
    protected $validators = array(
                                'iso_date' => array('NotEmpty', 'Date')
                             );
                            
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help budget-period_start-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'iso_date' => array(
                    'name' => 'iso_date',
                    'label' => 'Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help budget-period_start-iso_date"></div>',
                    'attrs' => array('class' => 'datepicker form-text', 'id' => 'iso_date')
                )
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    protected $required = true;
    protected $isAttributeSet = false;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    public function setOptions()
    {
//        $model = new Model_Wep();
//        $this->options['code'] = $model->getCodeArray('TransactionType', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->iso_date = (key_exists('@iso_date', $data))?$data['@iso_date']:$data['iso_date'];
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
    
    public function isRequired()
    {
        return $this->required;
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
        $data['@iso_date'] = $this->iso_date;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
    