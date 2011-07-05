<?php
class Iati_WEP_Activity_Title
{

    protected static $objectName = 'Title';
    protected $text = '';
    protected $xml_lang;
    protected static $activity_id;
    protected static $account_id;
    protected $title_id = 0;
    protected $tableName = 'iati_title';
    protected $html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><textarea rows="4" class ="input" name="%s">%s</textarea></dd>',
                        'xml_lang' => '<dt><label for="">%s</label><dt><dd><select name="%s">%s</select></dd>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="%s" class="title_id" value="%s"/></dd>', 
    );

    protected $validators = array(
                        'text' => 'NotEmpty',
                        'xml_lang' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
    );
    protected static $count = 0;
    protected $object_id;
    protected $remove;
    protected $options = array();
    protected $multiple = true;
    protected $error = array();
    protected $hasError = false;


    public function __construct($id = 0)
    {
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        //        print $this->object_id;
    }

    public function getTitleId()
    {
        return $this->title_id;
    }

    public function setHtml()
    {
        $this->html['text'] = sprintf($this->html['text'], 'Text',$this->decorateName('text'), $this->text);
        $this->html['xml_lang'] = sprintf($this->html['xml_lang'], 'Language', $this->decorateName('xml_lang'), $this->createOptionHtml('xml_lang'));
        $this->html['activity_id'] = sprintf($this->html['activity_id'],  self::$activity_id);
        $this->html['title_id'] = sprintf($this->html['title_id'], $this->decorateName('title_id'),  $this->title_id);

        //        print $this->text; print $this->xml_lang; print "<br>";
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
        if($data['@xml_lang']){
            $xml_lang = $data['@xml_lang'];
        }
        elseif($data['xml_lang']){
            $xml_lang = $data['xml_lang'];
        }
        $this->xml_lang = $xml_lang;

        $this->text = $data['text'];

        //        print $this->xml_lang; print $this->text;print "<br>";
    }

    public function setAll( $id = 0)
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', self::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');

        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }


    public function decorateName($name)
    {
        if($this->multiple){
            $name = $name . "[" . $this->object_id . "]";
        }
        //print $this->object;
        return $name;
    }

    public function toHtml($error = 0)
    {
        $style = ($this->object_id == 0)?"style= 'display:none'":'';
        $string = "<div id= 'new-div-$this->object_id' $style>";
        $htmlString = $string . implode("",array_values($this->html));
        $htmlString .= ($this->object_id > -1)?"<span class='remove'>Remove</a></div>" :"</div>";
        return $htmlString;

    }

    public function createOptionHtml($name){
        $optionArray = $this->options[$name];
        $string = '<option value="" label="Select anyone">Select anyone</option>';
        $stringSprint = '<option value= "%s" %s>%s</option>';
        foreach($optionArray as $key => $val){
            $_selected = ($this->$name == $key) ? 'selected="selected"' : '';
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

    public function getTableName()
    {
        return $this->tableName;
    }

    /*public function getProperties()
     {
     //            $this->setAll($account_id, $activity_id, $retrievedValues = array());
     return get_object_vars($this);
     }*/

    public function getProperties()
    {
        $data = array('@xml_lang', 'text');
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

    public function validate()
    {
        $data['xml_lang'] = $this->xml_lang;
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
        $data['@xml_lang'] = $this->xml_lang;
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
        $data['@xml_lang'] = $this->xml_lang;
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