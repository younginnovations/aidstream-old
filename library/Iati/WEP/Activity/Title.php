<?php
class Iati_WEP_Activity_Title extends Iati_WEP_Activity_ElementBase
{
    protected $text = '';
    protected $xml_lang;
    protected $title_id = 0;
    protected static $count = 0;
    protected $object_id;
   


    public function __construct($id = 0)
    {
        parent::__construct();
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        
        $this->objectName = 'Title';
        $this->tableName = 'iati_title';
        $this->html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><textarea rows="4" class ="input" name="%s">%s</textarea></dd>',
                        'xml_lang' => '<dt><label for="">%s</label><dt><dd><select name="%s">%s</select></dd>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="%s" class="title_id" value="%s"/></dd>', 
                    );
                    
        $this->validators = array(
                        'text' => 'NotEmpty',
                        'xml_lang' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
                        );
        $this->multiple = true;
//        $this->setProperties();
        //        print $this->object_id;
    }

    public function propertySetter($initial)
    {
//        $this->setAccountActivity($accountActivity);
        $this->setProperties($initial);
        $this->setAll();    
    }
    
    public function getTitleId()
    {
        return $this->title_id;
    }

    public function setHtml()
    {
        $this->html['text'] = sprintf($this->html['text'], 'Text',$this->decorateName('text'), $this->text);
        $this->html['xml_lang'] = sprintf($this->html['xml_lang'], 'Language', $this->decorateName('xml_lang'), $this->createOptionHtml('xml_lang'));
        $this->html['activity_id'] = sprintf($this->html['activity_id'],  parent::$activity_id);
        $this->html['title_id'] = sprintf($this->html['title_id'], $this->decorateName('title_id'),  $this->title_id);

        //        print $this->text; print $this->xml_lang; print "<br>";
        if($this->hasErrors()){
            foreach($this->error as $key => $eachError){
                $msg = array_values($eachError);
                $this->html[$key] .= "<p class='flash-message'>$msg[0]</p>";
            }
        }
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
        
        
    }

    public function setAll($id = 0)
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', parent::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');

        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }

    /*public function getObjectName()
    {
        return parent::$objectName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }
*/
    public function validate()
    {
        $data['xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        
        parent::validate($data);
    }

    public function hasErrors()
    {
        return $this->hasError;
    }

    public function insert()
    {
        $data['text'] = $this->text;
        $data['@xml_lang'] = $this->xml_lang;
        $data['activity_id'] = parent::$activity_id;

        $id = parent::insert($data);
        //print_r($id);//exit;
        $this->title_id = $id;
        $this->setHtml();
        /*$model = new Model_Wep();
        $title_id = $model->insertRowsToTable($this->tableName, $data);*/
        
        /*$activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = parent::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);*/
    }

    public function update()
    {
        $data['text'] = $this->text;
        $data['@xml_lang'] = $this->xml_lang;
        $data['activity_id'] = parent::$activity_id;
        $data['id'] = $this->title_id;
        /*$model = new Model_Wep();
        $id = $model->updateRowsToTable($this->tableName, $data);*/
        parent::update($data);
        
        /*$activity['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity['id'] = parent::$activity_id;
        $model->updateRowsToTable('iati_activity', $activity);*/
    }

    public function retrieve($activity_id)
    {
        $model = new Model_Wep();
        $rowSet = $model->listAll($this->tableName,'activity_id', $activity_id);
        return $rowSet;
    }
}