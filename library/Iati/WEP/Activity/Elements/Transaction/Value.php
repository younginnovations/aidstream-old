<?php 
class Iati_WEP_Activity_Elements_Transaction_Value extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'currency', 'value_date');
    protected $text;
    protected $currency;
    protected $value_date;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Value';
    protected $validators = array(
                                'text' => 'NotEmpty',
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
                    'label' => 'Currency',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'value_date' => array(
                    'name' => 'value_date',
                    'label' => 'Value Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'date', 'class'=>'datepicker form-text'),
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
    
        $this->setOptions();
    }
    
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['currency'] = $model->getCodeArray('Currency', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->currency = (key_exists('@currency', $data))?$data['@currency']:$data['currency'];
        $this->text = $data['text'];
        $this->value_date = (key_exists('@value_date', $data))?$data['@value_date']:$data['value_date'];
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
        $data['currency'] = $this->currency;
        $data['text'] = $this->text;
        $data['value_date'] = $this->value_date;
        
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
        $data['@currency'] = $this->currency;
        $data['text'] = $this->text;
        $data['@value_date'] = $this->value_date;
        return $data;
    }
}
