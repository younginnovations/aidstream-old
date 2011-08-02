<?php
class Iati_WEP_Activity_Elements_OtherIdentifier extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('text', 'owner_ref', 'owner_name');
    protected $text;
    protected $owner_ref;
    protected $owner_name;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'text' => 'NotEmpty',
                            );
    protected $className = 'OtherIdentifier';
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
                
                'owner_name' => array(
                    'name' => 'owner_name',
                    'label' => 'Owner Name',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'owner_ref' => array(
                    'name' => 'owner_ref',
                    'label' => 'Organisation Identfier',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
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
        $this->options['owner_ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
    }
    
    public function setAttributes ($data) {
        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->owner_ref = (key_exists('@owner_ref', $data))?$data['@owner_ref']:$data['owner_ref'];
        $this->owner_name = (key_exists('@owner_name', $data))?$data['@owner_name']:$data['owner_name'];
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
        $data['text'] = $this->text;
        $data['owner_ref'] = $this->owner_ref;
        $data['owner_name'] = $this->owner_name;
//        $data['activity_id'] = self::$activity_id;
        
        parent::validate($data);
    }
    
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@owner_ref'] = $this->owner_ref;
        $data['@owner_name'] = $this->owner_name;
        $data['text'] = $this->text;
        
        return $data;
    }
    
    public function checkPrivilege()
    {
        $userRole = new App_UserRole();
        $resource = new App_Resource();
        $resource->ownerUserId = $userRole->userId;
        if (!Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'OtherIdentifier')) {
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user/user/login';
            header("Location: http://$host$uri/$extra");
        }
    }

}
