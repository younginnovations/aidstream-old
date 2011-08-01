<?php 
class Iati_WEP_Activity_Elements_Transaction_Description extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'xml_lang');
    protected $text;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Description';
    protected $validators = array();
    
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
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
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
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->currency = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->text = $data['text'];
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }

    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function validate()
    {
        $data['id'] = $this->id;
        $data['code'] = $this->code;
        $data['text'] = $this->text;
        
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
        $data['@code'] = $this->code;
        $data['text'] = $this->text;
        
        return $data;
    }
    
}