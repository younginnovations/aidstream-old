<?php
class Iati_WEP_Activity_Elements_Identifier extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $attributes = array('id','text','activity_identifier');
    protected $text = '';
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'text' => array('NotEmpty',),
                                'activity_identifier' => array('NotEmpty')
                            );
    protected $className = 'Identifier';
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                //added for creating iati-identifier
                'reporting_org_ref' => array(
                    'name' => 'reporting_org_ref',
                    'html' => '<input type= "hidden" name="%(name)s" value = "%(value)s" id="reporting_org"/>'
                ),
                'activity_identifier' => array(
                    'name' => 'activity_identifier',
                    'label' => 'Activity Identifier',
                    'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s id="activity_identifier">%(value)s</textarea><div class="help activity_identifier"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'IATI Activity Identifier',
                    'html' => '<textarea rows="2" cols="20" readonly="true" name="%(name)s" %(attrs)s id="iati_identifier_text">%(value)s</textarea><div class="help identifier-text"></div>',
                    'attrs' => array('class' => array('form-text'))
                ),
    );
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    protected $required = true;
    
    
    public function __construct($id = 0)
    {
//        $this->checkPrivilege();
        parent::__construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }

    public function setOptions()
    {
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0;
        $this->text = $data['text'];
        $this->activity_identifier = $data['activity_identifier'];
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_DefaultFieldValues();
        $this->reporting_org_ref = $model->getByOrganisationId($identity->account_id, 'reporting_org_ref');
        
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
        $data['text'] = $this->text;
        $data['activity_identifier'] = $this->activity_identifier;
        //@todo parent id
//        $data['activity_id'] = parent :: $activity_id;
        
        parent::validate($data);
    }

    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['text'] = trim($this->text);
        $data['activity_identifier'] = str_replace(' ','',trim($this->activity_identifier));
        
        return $data;
    }
    
    public function checkPrivilege()
    {
        $userRole = new App_UserRole();
        $resource = new App_Resource();
        $resource->ownerUserId = $userRole->userId;
        if (!Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'IatiIdentifier')) {
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user/user/login';
            header("Location: http://$host$uri/$extra");
        }
    }
}
