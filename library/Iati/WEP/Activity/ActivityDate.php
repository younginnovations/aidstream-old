<?php
class Iati_WEP_Activity_ActivityDate
{
    protected static $objectName = 'ActivityDate';
    protected $text = '';
    protected $iso_date = '';
    protected $type = '';
    protected $xml_lang = '';
    protected static $activity_id;
    protected static $account_id;
    protected $title_id;
    protected $tableName = 'iati_activity_date';
    protected $html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'iso_date' =>'<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" class = "datepicker" id="datepicker" /></dd>',
                        'type' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select>',
                        'xml_lang' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="title_id" class ="title_id" value="%s"/></dd>', 
                    );
    protected $validators = array(
                        'type' => 'NotEmpty',
                        'text' => 'NotEmpty',
                    );
    protected static $count = 0;
    protected $object_id;
    protected $options = array();
    protected $multiple = true;
    protected $error = array();
    protected $hasError = false;
    
    public function __construct($id ='')
    {
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
    }
    
    public function setHtml()
    {
        $this->html['text'] = sprintf($this->html['text'], 'Text',$this->decorateName('text'), $this->text);
        $this->html['iso_date'] = sprintf($this->html['iso_date'], 'Iso Date',$this->decorateName('iso_date'), $this->iso_date);
        $this->html['type'] = sprintf($this->html['type'], 'Activity Date Type', $this->decorateName('type'), $this->createOptionHtml('type'));
        $this->html['xml_lang'] = sprintf($this->html['xml_lang'], 'Language', $this->decorateName('xml_lang'), $this->createOptionHtml('xml_lang'));
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
        
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->iso_date = (key_exists('@iso_date', $data))?$data['@iso_date']:$data['iso_date'];
        $this->text = $data['text'];

    }
    
       
    public function setAll( $id = '')
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', self::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');
        $this->options['type'] = $model->getCodeArray('ActivityDateType', null, '1');
        
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
        print $this->object;
        return $name;
    }

    public function toHtml($error = 0)
    {
        $style = ($this->object_id == 0)?"style= 'display:none'":'';
        $string = "<div id= 'new-div-$this->object_id' $style>";
        $htmlString = $string . implode("",array_values($this->html));
        $htmlString .= ($this->object_id > -1)?"<span class='remove'>Remove</span></div>" :"</div>";
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

    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function getObjectName()
    {
        return self::$objectName;
    }
    
    public function getProperties()
     {
         $data = array('@xml_lang', 'text', '@iso_date', '@type');
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
        $data['iso_date'] = $this->iso_date;
        $data['type'] = $this->type;
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
        $data['@iso_date'] = $this->iso_date;
        $data['@type'] = $this->type;
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        $data['activity_id'] = self::$activity_id;

        $model = new Model_Wep();
        $title_id = $model->insertRowsToTable($this->tableName, $data);
        
        $activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = self::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);
    }
    
    public function update(){
        $data['@iso_date'] = $this->iso_date;
        $data['@type'] = $this->type;
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        $data['activity_id'] = self::$activity_id;
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