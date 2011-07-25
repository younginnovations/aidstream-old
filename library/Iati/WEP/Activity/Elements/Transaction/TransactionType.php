<?php 
class Iati_WEP_Activity_Elements_Transaction_TransactionType extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'code');
    protected $text;
    protected $code;
    protected $id = 0;
    protected $options = array();
    protected $className = 'TransactionType';
    
    protected $validators = array(
                                'text' => 'NotEmpty',
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
                    'attrs' => array('id' => 'id')
                ),
                'code' => array(
                    'name' => 'code',
                    'label' => 'Transaction Type Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                )
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
        $this->options['code'] = $model->getCodeArray('TransactionType', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->code = (key_exists('@code', $data))?$data['@code']:$data['code'];
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
        $data['code'] = $this->code;
        $data['text'] = $this->text;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])){ continue; }
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData))) {  continue; }
            
            $string = "Zend_Validate_". $this->validators[$key];
            $validator = new $string();
            
            if(!$validator->isValid($eachData)){
//                print "dd";exit;
                $this->error[$key] = $validator->getMessages();
                $this->hasError = true;

            }
        }
    }
    
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@code'] = $this->code;
        $data['text'] = $this->text;
        $data['transaction_id'] = $this->transaction_id;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}