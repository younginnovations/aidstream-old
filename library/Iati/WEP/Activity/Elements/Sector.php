<?php
class Iati_WEP_Activity_Elements_Sector extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'text', 'vocabulary', 'code', 'percentage', 'xml_lang');
    protected $text;
    protected $code;
    protected $non_dac_code;
    protected $vocabulary;
    protected $percentage;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                //'vocabulary' => array('NotEmpty'),
                                'percentage' => array('Int')
                            );
    protected $className = 'Sector';
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'vocabulary' => array(
                    'name' => 'vocabulary',
                    'label' => 'Vocabulary',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help sector-vocabulary"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select', 'vocabulary_value'))
                ),
                'code' => array(
                    'name' => 'code',
                    'label' => 'Sector',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help sector-code"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select', 'sector_value'))
                ),
                'non_dac_code' => array(
                    'name' => 'non_dac_code',
                    'label' => 'Sector',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s"/><div class="help sector-non_dac_code"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-text', 'non_dac_code'))
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help sector-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'percentage' => array(
                    'name' => 'percentage',
                    'label' => 'Percentage',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help sector-percentage"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help sector-xml_lang"></div>',
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
        $this->non_dac_code = (key_exists('@code', $data))?$data['@code']:$data['non_dac_code'];
        $this->percentage = (key_exists('@percentage', $data))?$data['@percentage']:$data['percentage'];
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->text = $data['text'];
        
        //special logic only for sector
        if($this->vocabulary == '' || $this->vocabulary == '4'){
            $this->attributes_html['non_dac_code']['attrs']['class'][] = 'hide-div';
        }
        else{
            $this->attributes_html['code']['attrs']['class'][] = 'hide-div';
        }
        
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
        $data['code'] = ($this->code)?$this->code:$this->non_dac_code;
        $data['vocabulary'] = $this->vocabulary;
        $data['percentage'] = $this->percentage;
        $data['text'] = $this->text;
        parent :: validate($data);
        
    }
    
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@xml_lang'] = $this->xml_lang;
        $data['@code'] = ($this->code)?$this->code:$this->non_dac_code;
        $data['@vocabulary'] = $this->vocabulary;
        $data['@percentage'] = $this->percentage;
        $data['text'] = $this->text;
        
        return $data;
    }


}
