<?php
class Iati_WEP_Activity_IatiIdentifier extends Iati_WEP_Activity_ElementBase
{
    protected $text = '';
    protected $title_id = 0;
    protected static $count = 0;
    protected $object_id;
   


    public function __construct($id = 0)
    {
        parent::__construct();
        $this->title_id = $id;
        $this->object_id = self::$count;
        self::$count += 1;
        
        $this->objectName = 'IatiIdentifier';
        $this->tableName = 'iati_identifier';
        $this->html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="title_id" class ="title_id" value="%s"/></dd>', 
                    );
                    
        $this->validators = array(
                        'text' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
                    );
        $this->multiple = false;
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
        $this->text = $data['text'];
    }

    public function setAll( $id = 0)
    {
        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }

    public function validate()
    {
        $data['text'] = $this->text;
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

        parent::insert($data);
    }

    public function update()
    {
        $data['text'] = $this->text;
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