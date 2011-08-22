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
                                'gazetteer_ref' => 'NotEmpty',
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
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData)) || 
            (empty($this->required))) {  continue; }
            
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
        $data['@gazetteer_ref'] = $this->gazetteer_ref;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
