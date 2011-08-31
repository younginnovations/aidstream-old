<?php 
class Iati_WEP_Activity_Elements_Transaction_DisbursementChannel extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('id', 'text', 'code');
    protected $validAttribs = array('text', '@code');
    protected $text;
    protected $code;
    protected $id = 0;
    protected $options = array();
    protected $className = 'DisbursementChannel';
    protected $validators = array(
                                'code' => 'NotEmpty',
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
                'code' => array(
                    'name' => 'code',
                    'label' => 'Disbursement Channel Code',
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
        $this->options['code'] = $model->getCodeArray('DisbursementChannel', null, '1');
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->code = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->text = $data['text'];
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
        $data['code'] = $this->code;
        $data['text'] = $this->text;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])){ continue; }
            
            if((in_array('NotEmpty', $this->validators[$key]) == false) && (empty($eachData)) && 
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
        $data['@code'] = $this->code;
        $data['text'] = $this->text;
        
//        print_r($data);exit;
        return $data;
    }
}
