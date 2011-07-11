<?php
class Iati_WEP_Activity_ReportingOrganisation extends Iati_WEP_Activity_ElementBase
{
    protected $text = '';
    protected $type = '';
    protected $iso_date = '';
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
        
        $this->objectName = 'ReportingOrganisation';
        $this->tableName = 'iati_reporting_org';
        $this->html = array(
                        'text' => '<dt><label for="">%s</label></dt><dd><input type= "text" name="%s" value="%s" /></dd>',
                        'ref' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'type' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'xml_lang' => '<dt><label for="">%s</label></dt><dd><select name="%s">%s</select></dd>',
                        'activity_id' => '<dd><input type="hidden" name="activity_id" value="%s"/></dd>',
                        'title_id' => '<dd><input type="hidden" name="title_id" class ="title_id" value="%s"/></dd>', 
                    );
                    
        $this->validators = array(
                        'ref' => 'NotEmpty',
                        'text' => 'NotEmpty',
                        'activity_id' => 'NotEmpty',
                    );
        $this->multiple = false;
//        $this->setProperties();
        //        print $this->object_id;
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
        $this->html['ref'] = sprintf($this->html['ref'], 'Organisation Identifier', $this->decorateName('ref'), $this->createOptionHtml('ref'));
        $this->html['type'] = sprintf($this->html['type'], 'Organisation Type', $this->decorateName('type'), $this->createOptionHtml('type'));
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
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->text = $data['text'];
        
        
    }

    public function setAll( $id = 0)
    {
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values',  'account_id', self::$account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
         
        $this->options['xml_lang'] = $model->getCodeArray('Language',null,'1');
        $this->options['ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
        $this->options['type'] = $model->getCodeArray('OrganisationType', null, '1');
        if($id){
            $this->title_id = $id;
        }
        $this->setHtml();
    }

    public function validate()
    {
        $data['xml_lang'] = $this->xml_lang;
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
        $data['iso_date'] = $this->iso_date;
        $data['type'] = $this->type;
        $data['xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;

        parent::insert($data);
    }

    public function update()
    {
        $data['text'] = $this->text;
        $data['@xml_lang'] = $this->xml_lang;
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