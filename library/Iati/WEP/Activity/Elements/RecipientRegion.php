<?php
class Iati_WEP_Activity_Elements_RecipientRegion extends Iati_WEP_Activity_Elements_ElementBase
{  
    protected $attributes = array('id','text', 'code', 'percentage',  'xml_lang');
    protected $text;
    protected $code;
    protected $percentage;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'code' => array('NotEmpty',),
                                'percentage' => array('Float')
                            );
    protected $className = 'RecipientRegion';

    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Name',
                    'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help recipient_region-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                
                'code' => array(
                    'name' => 'code',
                    'label' => 'Region Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help recipient_region-code"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'percentage' => array(
                    'name' => 'percentage',
                    'label' => 'Percentage',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help recipient_region-percentage"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help recipient_region-xml_lang"></div>',
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
//        $this->checkPrivilege();
        parent::__construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['code'] = $model->getCodeArray('Region', null, '1');
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {
        
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->percentage = (key_exists('@percentage', $data))?$data['@percentage']:$data['percentage'];
        $this->code = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->text = $data['text'];       
        
    }

     public function checkPrivilege()
    {
        $userRole = new App_UserRole();
        $resource = new App_Resource();
        $resource->ownerUserId = $userRole->userId;
        if (!Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'RecipientRegion')) {
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user/user/login';
            header("Location: http://$host$uri/$extra");
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
    
    
    public function getCleanedData()
    {
        $data['id'] = $this->id;
        $data['@code'] = $this->code;
        $data['@percentage'] = $this->percentage;
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        return $data;
    }

    public function validate()
    {
        $data['code'] = $this->code;
        $data['percentage'] = $this->percentage;
        $data['xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        
        parent::validate($data);
    }

    public function hasErrors()
    {
        return $this->hasError;
    }
}
