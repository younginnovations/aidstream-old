<?php 
class Iati_WEP_Activity_Elements_Result_Indicator extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('id', 'measure');
    protected $measure;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Indicator';
    
    protected $validators = array(
                                'measure' => 'NotEmpty',
                            );
                            
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'measure' => array(
                    
                    'name' => 'measure',
                    'label' => 'Measure',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help result-indicator-measure"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = true;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['measure'] = $model->getCodeArray('IndicatorMeasure', null, '1');
    }
    
    public function setAttributes ($data) {
        
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->code = (key_exists('@measure', $data))?$data['@measure']:$data['measure'];
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
        $data['measure'] = $this->measure;
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
        $data['@measure'] = $this->measure;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
