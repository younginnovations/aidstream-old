<?php
class Iati_WEP_Activity_Elements_ReportingOrg extends Iati_WEP_Activity_Elements_ElementBase
{  
    protected $attributes = array('id', 'text', 'xml_lang', 'ref', 'type');
    protected $text = '';
    protected $type = '';
    protected $ref = '';
    protected $xml_lang;
    protected $id = 0;
    protected $options = array();
    protected $validators = array(
                                'ref' => array('NotEmpty'),
                            );
    protected $className = 'ReportingOrg';
    protected $displayName = 'ReportingOrganisation';
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'ref' => array(
                    'name' => 'ref',
                    'label' => 'Organisation Identifier',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" /><div class="help reporting_org-ref"></div>',
                    'attrs' => array('class' => array('form-text'), 'disabled' => 'disabled')
                ),
                'type' => array(
                    'name' => 'type',
                    'label' => 'Organisation Type',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help reporting_org-type"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select') , 'disabled' => 'disabled')
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<textarea rows="2" cols="20" name="%(name)s" %(attrs)s>%(value)s</textarea><div class="help reporting_org-text"></div>',
                    'attrs' => array('class' => array('form-text'), 'disabled' => 'disabled')
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select><div class="help reporting_org-xml_lang"></div>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select') , 'disabled' => 'disabled')
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
        parent::__construct();
        $this->objectId = self::$count;
        self::$count += 1;
        $this->setOptions();
    }

    public function setOptions()
    {
        $model = new Model_Wep();
        //$this->options['ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
        $this->options['type'] = $model->getcodeArray('OrganisationType', null, '1');
        $this->options['xml_lang'] = $model->getCodeArray('Language', null, '1');
    }
    
    public function setAttributes ($data) {

        $this->id = (key_exists('id', $data))?$data['id']:0;
        $this->xml_lang = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->type = (key_exists('@type', $data))?$data['@type']:$data['type'];
        $this->text = $data['text'];
        
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
        $data['xml_lang'] = $this->xml_lang;
        $data['type'] = $this->type;
        $data['ref'] = $this->ref;
        $data['text'] = $this->text;
        //@todo parent id
//        $data['activity_id'] = parent :: $activity_id;
        
        parent::validate($data);
    }

    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@type'] = $this->type;
        $data['@ref'] = $this->ref;
        $data['@xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        
        return $data;
    }
}
