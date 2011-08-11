<?php 
class Iati_WEP_Activity_Elements_Result_Indicator_Title extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('id', 'text', 'xml_lang');
    protected $text;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Title';
    
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
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                )
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
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {
//        print_r($data);exit;
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
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
        $data['xml_lang'] = $this->xml_lang;
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
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}
