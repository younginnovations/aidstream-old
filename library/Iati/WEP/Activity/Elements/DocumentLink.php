<?php
class Iati_WEP_Activity_Elements_DocumentLink extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'url', 'format');
    protected $url;
    protected $format;
    protected $id = 0;
    protected $options = array();
    protected $multiple = true;
    protected $attributes_html = array(
                    'id' => array(
                        'name' => 'id',
                        'label' => '',
                        'html' => '<input type="hidden" name="%(name)s" value="%(value)s" />',
                    ),
                    'url' => array(
                        'name' => 'url',
                        'label' => 'Document Url',
                        'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help document_link-url"></div>',
                        'attrs' => array('class' => array('form-text'))
                    ),
                    'format' => array(
                        'name' => 'format',
                        'label' => 'Document Format',
                        'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help document_link-format"></div>',
                        'options' => '',
                        'attrs' => array('class' => array('form-select'))
                    )
                    
    );
    protected $className = 'DocumentLink';
    
    protected $validators = array('url' => array('NotEmpty' , 'App_Validate_Url'));
    protected static $count = 0;
    protected $objectId;
    protected $error = array(); 
    protected $hasError = false;
    
    public function __construct()
    {
        parent :: __construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }
    
    public function setAttributes ($data) {
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->url = (key_exists('@url', $data))?$data['@url']:$data['url'];
        $this->format = (key_exists('@format', $data))?$data['@format']:$data['format'];
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        
        $this->options['format'] = $model->getCodeArray('FileFormat', null, '1');
    }
    
    public function getOptions($attr)
    {
        return $this->options[$attr];
    }
    
    public function getAttributes () {
        return $this->attributes;
    }
    public function getClassName ()
    {
        return $this->className;
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }
    
    public function validate(){
        $data['id'] = $this->id;
        $data['url'] = $this->url;
        $data['format'] = $this->format;
        parent :: validate($data);
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function getCleanedData(){
        $data = array();
        $data['id'] = $this->id;
        $data['@url'] = $this->url;
        $data['@format'] = $this->format;
        
        return $data;
    }
}