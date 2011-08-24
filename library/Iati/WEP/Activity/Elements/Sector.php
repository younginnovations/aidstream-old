<?php
class Iati_WEP_Activity_Elements_Sector extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'text', 'vocabulary', 'code', 'percentage', 'xml_lang');
    protected $text;
    protected $code;
    protected $vocabulary;
    protected $percentage;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'code' => 'NotEmpty',
                                'percentage' => 'Int',
                            );
    protected $className = 'Sector';
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
                    'label' => 'Sector',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'vocabulary' => array(
                    'name' => 'vocabulary',
                    'label' => 'Vocabulary',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'percentage' => array(
                    'name' => 'percentage',
                    'label' => 'Percentage',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
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
   
    public function __construct()
    {
        parent::__construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['vocabulary'] = $model->getCodeArray('Vocabulary', NULL, '1');
        $this->options['code'] = $model->getCodeArray('Sector', NULL, '1');
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0;
        
        $this->vocabulary = (key_exists('@vocabulary', $data))?$data['@vocabulary']:$data['vocabulary'];
        $this->code = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->percentage = (key_exists('@percentage', $data))?$data['@percentage']:$data['percentage'];
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
        $data['code'] = $this->code;
        $data['vocabulary'] = $this->vocabulary;
        $data['percentage'] = $this->percentage;
        $data['text'] = $this->text;
        
        parent :: validate($data);
    }
    
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@xml_lang'] = $this->xml_lang;
        $data['@code'] = $this->code;
        $data['@vocabulary'] = $this->vocabulary;
        $data['@percentage'] = $this->percentage;
        $data['text'] = $this->text;
        
        return $data;
    }


}
