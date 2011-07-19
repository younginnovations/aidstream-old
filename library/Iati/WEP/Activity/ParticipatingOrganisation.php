<?php
class Iati_WEP_Activity_ParticipatingOrganisation extends Iati_WEP_Activity_ElementBase
{
    protected $text = '';
    protected $role;
    protected $ref;
    protected $type = '';
    protected $xml_lang = '';
    protected $title_id = 0;
    protected static $count = 0;
    protected $object_id;
   


    public function __construct($id = 0)
    {
        $this->checkPrivilege();
        parent::__construct();
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        
        $this->objectName = 'ParticipatingOrganisation';
        $this->tableName = 'iati_participating_org';
        $this->html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'role' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'ref' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'type' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'xml_lang' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'activity_id' => '<input type="hidden" name="activity_id" value="%s"/>',
                        'title_id' => '<input type="hidden" name="title_id" class ="title_id" value="%s"/>',    
                    );
                    
        $this->validators = array(
                        'role' => 'NotEmpty',
                        'text' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
                        );
        $this->multiple = true;
//        $this->setProperties();
        //        print $this->object_id;
    }

     public function checkPrivilege()
    {
        $userRole = new App_UserRole();
        $resource = new App_Resource();
        $resource->ownerUserId = $userRole->userId;
        if (!Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'ParticipatingOrganisation')) {
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'user/user/login';
            header("Location: http://$host$uri/$extra");
        }
    }

    public function propertySetter($initial, $title_id = 0)
    {
//        $this->setAccountActivity($accountActivity);
        $this->setProperties($initial);
        $this->setAll($title_id);    
    }
    
    public function getTitleId()
    {
        return $this->title_id;
    }

    public function setHtml()
    {
        $this->html['text'] = sprintf($this->html['text'], 'Text',$this->decorateName('text'), $this->text);
        $this->html['role'] = sprintf($this->html['role'], 'Organisation Role',$this->decorateName('role'), $this->createOptionHtml('role'));
        $this->html['type'] = sprintf($this->html['type'], 'Organisation Type', $this->decorateName('type'), $this->createOptionHtml('type'));
        $this->html['ref'] =  sprintf($this->html['ref'], 'Organisation Identifier', $this->decorateName('ref'), $this->createOptionHtml('ref'));
        $this->html['xml_lang'] = sprintf($this->html['xml_lang'], 'Language', $this->decorateName('xml_lang'), $this->createOptionHtml('xml_lang'));
        $this->html['activity_id'] = sprintf($this->html['activity_id'],  self::$activity_id);
        $this->html['title_id'] = sprintf($this->html['title_id'],  $this->title_id);
        //        print $this->text; print $this->xml_lang; print "<br>";
        if($this->hasErrors()){
            foreach($this->error as $key => $eachError){
                $msg = array_values($eachError);
                $this->html[$key] .= "<p class='flash-message'>$msg[0]</p>";
            }
        }
    }

    public function setProperties($data){
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->role = (key_exists('@role', $data))?$data['@role']:$data['role'];
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->text = $data['text'];       
    }

    public function setAll($id = 0)
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', parent::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');
        $this->options['type'] = $model->getCodeArray('OrganisationType', null, '1');
        $this->options['ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
        $this->options['role'] = $model->getCodeArray('OrganisationRole', null, '1');

        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }
    
    public function getData()
    {
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
        $data['activity_id'] = parent::$activity_id;
        
        parent::validate($data);
    }

    public function hasErrors()
    {
        return $this->hasError;
    }

    public function insert()
    {
        $data = $this->getData();

        $id = parent::insert($data);
        $this->title_id = $id;
        $this->setHtml();
    }

    public function update()
    {
        $data = $this->getData();
        $data['id'] = $this->title_id;
        parent::update($data);
    }

    public function retrieve($activity_id)
    {
        $model = new Model_Wep();
        $rowSet = $model->listAll($this->tableName,'activity_id', $activity_id);
        return $rowSet;
    }
}