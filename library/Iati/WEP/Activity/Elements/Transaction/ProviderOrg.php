<?php 
class Iati_WEP_Activity_Elements_Transaction_ProviderOrg extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'ref', 'provider_activity_id');
    protected $text;
    protected $ref;
    protected $provider_activity_id;
    protected $id = 0;
    protected $options = array();
    protected $className = 'ProviderOrg';
    protected $validators= array('ref' => 'NotEmpty');
    
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
                'ref' => array(
                    'name' => 'ref',
                    'label' => 'Organisation Identifier Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                    'attrs' => array('class' => array('form-select'))
                ),
                'provider_activity_id' => array(
                    'name' => 'provider_activity_id',
                    'label' => 'Provider Activity Id',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('class' => array('form-text'))
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;
    protected $required = false;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['ref'] = $model->getCodeArray('OrganisationIdentifier', null, '1');
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
//        print_r($data);exit;
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->text = $data['text'];
        $this->provider_activity_id = key_exists('@provider_activity_id', $data)?$data['@provider_activity_id']:$data['provider_activity_id'];
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }
    
    public function validate()
    {
        $data['id'] = $this->id;
        $data['ref'] = $this->ref;
        $data['provider_activity_id'] = $this->provider_activity_id;
        $data['text'] = $this->text;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])) continue;
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData)) || 
            (empty($this->required)))  continue;
            
            $string = "Zend_Validate_". $this->validators[$key];
            $validator = new $string();
            
            if(!$validator->isValid($eachData)){
                
                $this->error[$key] = $validator->getMessages();
                $this->hasError = true;

            }
        }
    }
public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@ref'] = $this->ref;
        $data['text'] = $this->text;
        $data['@provider_activity_id'] = $this->provider_activity_id;
        return $data;
    }
}
