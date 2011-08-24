<?php
class Iati_WEP_Activity_Elements_ParticipatingOrg extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id', 'text', 'role', 'ref', 'type', 'xml_lang');
    protected $text;
    protected $ref;
    protected $role;
    protected $type;
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'role' => 'NotEmpty',
                            );
    protected $className = 'ParticipatingOrg';

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

                'role' => array(
                    'name' => 'role',
                    'label' => 'Organisation Role',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'ref' => array(
                    'name' => 'ref',
                    'label' => 'Organisation Identfier',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'type' => array(
                    'name' => 'type',
                    'label' => 'Organisation Type',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
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
//        $this->checkPrivilege($this->className);
        parent::__construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }

    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
        $this->options['role'] = $model->getCodeArray('OrganisationRole', null, '1');
        $this->options['type'] = $model->getCodeArray('OrganisationType', null, '1');
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }

    public function setAttributes ($data) {

        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->role = (key_exists('@role', $data))?$data['@role']:$data['role'];
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->text = $data['text'];       
        
//        print_r($data);exit;
    }

    }

//     public function checkPrivilege()
//    {
//        $userRole = new App_UserRole();
//        $resource = new App_Resource();
//        $resource->ownerUserId = $userRole->userId;
//        if (!Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'ParticipatingOrg')) {
//            $host  = $_SERVER['HTTP_HOST'];
//            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//            $extra = 'user/user/login';
//            header("Location: http://$host$uri/$extra");
//        }
//    }


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
        $data['@ref'] = $this->ref;
        $data['@role'] = $this->role;
        $data['@type'] = $this->type;
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        return $data;
    }

    public function validate()
    {
        $data['ref'] = $this->ref;
        $data['role'] = $this->role;
        $data['type'] = $this->type;
        $data['xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;

        parent::validate($data);
    }

    public function hasErrors()
    {
        return $this->hasError;
    }
}
