<?php
class Iati_WEP_Activity_Elements_ElementBase
{
    /*protected static $activity_id;
    protected static $account_id;*/
    protected static $objectName = '';
    //protected $text = '';
//    protected $xml_lang;
    //protected $title_id = 0;
    protected $tableName = '';
    protected $html = array();

    protected $validators = array();
    //protected static $count = 0;
    //protected $object_id;
    protected $remove;
    protected $options = array();
    protected $multiple;
    protected $error = array();
    protected $hasError = false;

     public function __construct()
    {
        
    }
    
    /**
     * setAccountActivity sets the properties $activity_id and $account_id
     * @param $array - contains account_id and activity_id in their respective indexes
     * 
     */
    public function setAccountActivity($array)
    {
        /*self::$account_id = $array['account_id'];
        self::$activity_id = $array['activity_id'];*/
    }

    /**
     * setProperties is an abstract class and all the subclasses should have its implementation
     * it sets the element attributes eg: text, ref, role etc
     * @param array
     */
//    abstract public function setProperties($data);

    /**
     * decorateName decorates the form html name according to the occurance of the element in the form
     * @param string $name
     * @return if the occurance of the element is multiple, it appends "[num]" (num is obtained form object_id)
     * else returns as it is.
     */
    public function decorateName($name)
    {
        if($this->multiple){
            $name = $name . "[" . $this->object_id . "]";
        }
        return $name;
    }

    /**
     * 
     * @return html string
     */
    public function toHtml()
    {
//        print $this->object_id;
        $style = ($this->object_id == 0)?"style= 'display:none'":'';
        $string = "<div id= 'new-div-$this->object_id' $style>";
        $htmlString = $string . implode("",array_values($this->html));
        $htmlString .= ($this->object_id >= 0)?"<span class='remove'>Remove</a></div>" :"</div>";
        return $htmlString;
    }

    public function createOptionHtml($name)
    {
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

    
    public function getOptions()
    {
        return $this->options;
    }

    public function getObjectName()
    {
        return self::$objectName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }
    
    
    public function validate($data)
    {
        print_r($data);exit;
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
    
    public function getErrorMessage($attr)
    {
        return (isset($this->error[$attr])) ? $this->error[$attr] : NULL;
    }
    
    public function hasErrors()
    {
        return $this->hasError;
    }

    public function insert($data)
    {
        $model = new Model_Wep();
        $title_id = $model->insertRowsToTable($this->tableName, $data);
        
        $activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = self::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);
        return $title_id;
    }

    public function update($data)
    {
        $model = new Model_Wep();
        $id = $model->updateRowsToTable($this->tableName, $data);
        
        $activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = self::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);
    }

    public function retrieve($data)
    {
        $model = new Model_Wep();
        $rowSet = $model->listAll($this->tableName,'activity_id', $activity_id);
        return $rowSet;
    }
}
