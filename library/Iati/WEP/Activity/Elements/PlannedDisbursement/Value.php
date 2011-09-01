<?php 
class Iati_WEP_Activity_Elements_PlannedDisbursement_Value 
                                extends Iati_WEP_Activity_Elements_PlannedDisbursement
{
    protected $attributes = array('id', 'text', 'value_date', 'currency');
    protected $text;
    protected $currency;
    protected $value_date;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Value';
    
    protected $validators = array(
                                'value_date' => array('NotEmpty', 'Date'),
                                'text' => array('NotEmpty', 'Int'),
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
                'currency' => array(
                    'name' => 'currency',
                    'label' => 'currency',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                    ),
                'value_date' => array(
                    'name' => 'value_date',
                    'label' => 'Value Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text', 'datepicker'), 'id' => 'iso_date')
                )
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    protected $required = true;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['currency'] = $model->getCodeArray('Currency', null, '1');
    }
    
    public function setAttributes ($data) {
//        print_r($data);exit;
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->currency = (key_exists('@currency', $data))?$data['@currency']:$data['currency'];
        $this->value_date = (key_exists('@value_date', $data))?$data['@value_date']:$data['value_date'];
        $this->text = $data['text'];
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
        $data['value_date'] = $this->value_date;
        $data['currency'] = $this->currency;
        $data['text'] = $this->text;
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
        $data ['id'] = $this->id;
        $data['@value_date'] = $this->value_date;
        $data['@currency'] = $this->currency;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
    