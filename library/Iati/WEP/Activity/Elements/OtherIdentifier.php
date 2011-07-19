<?php
class Iati_WEP_Activity_Elements_OtherIdentifier extends Iati_WEP_Activity_Elements_ElementBase
{
    protected $text = '';
    protected $owner_ref = '';
    protected $owner_name = '';
    protected $title_id = 0;
    protected static $count = 0;
    protected $object_id;
   


    public function __construct($id = 0)
    {
        parent::__construct();
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        
        $this->objectName = 'OtherIdentifier';
        $this->tableName = 'iati_other_identifier';
        $this->html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'owner_ref' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'owner_name' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="title_id" class ="title_id" value="%s"/></dd>', 
                    );
                    
        $this->validators = array(
                        'text' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
                    );
        $this->multiple = true;
//        $this->setProperties();
        //        print $this->object_id;
    }
    
    public function propertySetter($initial, $title_id = 0)
    {
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
        $this->html['owner_ref'] = sprintf($this->html['owner_ref'], 'Organisation Identifier', $this->decorateName('owner_ref'), $this->createOptionHtml('owner_ref'));
        $this->html['owner_name'] = sprintf($this->html['owner_name'], 'Organisation Name',$this->decorateName('owner_name'), $this->owner_name);
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
        $this->owner_ref = (key_exists('@owner_ref', $data))?$data['@owner_ref']:$data['owner_ref'];
        $this->owner_name = (key_exists('@owner_name', $data))?$data['@owner_name']:$data['owner_name'];
        $this->text = $data['text'];
    }

    public function setAll( $id = 0)
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', self::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['owner_ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }

    public function validate()
    {
        $data['text'] = $this->text;
        $data['owner_ref'] = $this->owner_ref;
        $data['owner_name'] = $this->owner_name;
        $data['activity_id'] = self::$activity_id;
        
        parent::validate($data);
    }

    public function hasErrors()
    {
        return $this->hasError;
    }

    public function insert()
    {
        $data['text'] = $this->text;
        $data['@owner_name'] = $this->owner_name;
        $data['@owner_ref'] = $this->owner_ref;
        $data['activity_id'] = parent::$activity_id;
        parent::insert($data);
    }

    public function update()
    {
        $data['text'] = $this->text;
        $data['text'] = $this->text;
        $data['@owner_name'] = $this->owner_name;
        $data['activity_id'] = parent::$activity_id;
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
