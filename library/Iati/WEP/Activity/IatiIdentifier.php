<?php
class Iati_WEP_Activity_IatiIdentifier
{
    protected static $objectName = 'IatiIdentifier';
    protected $text = '';
    protected static $activity_id;
    protected static $account_id;
    protected $title_id;
    protected $tableName = 'iati_identifier';
    protected $html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'activity_id' => '<input type="hidden" name="activity_id" value="%s"/>',
                        'title_id' => '<input type="hidden" name="title_id" class ="title_id" value="%s"/>', 
    );

    protected $validators = array(
                        'text' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
    );
    protected static $count = 0;
    protected $object_id;
    protected $options = array();
    protected $multiple = false;
    protected $error = array();
    protected $hasError = false;


    public function __construct($id ='')
    {
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        //        print $this->object_id;
    }

    public function setHtml()
    {
        $this->html['text'] = sprintf($this->html['text'], 'Text',$this->decorateName('text'), $this->text);
        $this->html['activity_id'] = sprintf($this->html['activity_id'],  self::$activity_id);
        $this->html['title_id'] = sprintf($this->html['title_id'],  $this->title_id);

        if($this->hasErrors()){
            foreach($this->error as $key => $eachError){
                $msg = array_values($eachError);
                $this->html[$key] .= "<p class='flash-message'>$msg[0]</p>";
            }
        }
    }

    public function setAccountAcitivty($array)
    {
        self::$account_id = $array['account_id'];
        self::$activity_id = $array['activity_id'];
    }

    public function setProperties($data){
        //        print $data['text'];
        $this->text = $data['text'];
    }

    public function setAll( $id = '')
    {
        /*$model = new Model_Wep();
         $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', self::$account_id);
         $defaults = $defaultFieldValues->getDefaultFields();*/
         
        //        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');
        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }


    public function decorateName($name)
    {
        //        if($this->multiple){
        $name = $name . "[" . $this->object_id . "]";
        //        }
        return $name;
    }

    public function toHtml($error = 0)
    {
        /*if($error){
         //            $this->html
         }
         else{*/

        $style = ($this->object_id == 0)?"style= 'display:none'":'';
        $string = "<div id= '$this->object_id' $style>";
        $htmlString = $string . implode("",array_values($this->html));
        $htmlString .= ($this->object_id > 1)?"<span class ='remove'>Remove</span></div>" :"</div>";
        return $htmlString;
        //        }

    }

    public function createOptionHtml($name){
        $optionArray = $this->options[$name];
        $string = '<option value="" label="Select anyone">Select anyone</option>';
        $stringSprint = '<option value= "%s" %s>%s</option>';
        foreach($optionArray as $key => $val){
            $_selected = ($this->xml_lang == $key) ? 'selected="selected"' : '';
            $string .= sprintf($stringSprint,$key,$_selected,$val);
        }

        return $string;
    }

    public function hasMultiple()
    {
        return $this->multiple;
    }

    public function getObjectName()
    {
        return self::$objectName;
    }

    /*public function getProperties()
     {
     //            $this->setAll($account_id, $activity_id, $retrievedValues = array());
     return get_object_vars($this);
     }*/

    public function getProperties()
    {
        $data = array('text');
        return $data;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function validate()
    {
        $data['text'] = $this->text;
        //        $data['activity_id'] = self::$activity_id;
        //        print_r($data);exit();
        foreach($data as $key => $eachData){

            if(empty($this->validators[$key])){
                continue;
            }
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData))){
                continue;
            }
            $string = "Zend_Validate_". $this->validators[$key];
            $validator = new $string();
             
            if(!$validator->isValid($eachData)){
                //                print_r($validator->getMessages());
                $this->error[$key] = $validator->getMessages();
                $this->hasError = true;

            }
        }

    }

    public function hasErrors()
    {
        return $this->hasError;
    }

    public function insert()
    {
        $data['text'] = $this->text;
        $data['activity_id'] = self::$activity_id;

        $model = new Model_Wep();
        $title_id = $model->insertRowsToTable($this->tableName, $data);
        
        $activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = self::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);
    }

    public function update()
    {
        $data['text'] = $this->text;
        $data['activity_id'] = self::$activity_id;
        $data['id'] = $this->title_id;
        $model = new Model_Wep();
        $id = $model->updateRowsToTable($this->tableName, $data);
        
        $activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = self::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);
    }

    public function retrieve($activity_id)
    {
        $model = new Model_Wep();
        $rowSet = $model->listAll($this->tableName,'activity_id', $activity_id);
        return $rowSet;
    }
}