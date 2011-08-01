<?php 
class Iati_WEP_Activity_Elements_Transaction_ReceiverOrg extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'ref', 'receiver_activity_id');
    protected $text;
    protected $ref;
    protected $receiver_activity_id;
    protected $id = 0;
    protected $options = array();
    protected $className = 'ReceiverOrg';
    
    protected $validators = array(
                                /*'text' => 'NotEmpty',
                                'code' => 'NotEmpty',*/
                            );
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'ref' => array(
                    'name' => 'ref',
                    'label' => 'Organisation Identifier Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                ),
                'receiver_activity_id' => array(
                    'name' => 'receiver_activity_id',
                    'label' => 'Receiver Activity Id',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
    );
    
    protected static $count = 0;
    protected $objectId;
    protected $error = array();
    protected $hasError = false;
    protected $multiple = false;

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
        $this->ref = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->text = $data['text'];
        $this->receiver_activity_id = (key_exists('@receiver_activity_id', $data))?$data['@receiver_activity_id']:$data['receiver_activity_id'];
    }
    
    public function getValidator($attr)
    {
        return $this->validators[$attr];
    }
    
    public function validate()
    {
        $data['id'] = $this->id;
        $data['ref'] = $this->ref;
        $data['text'] = $this->text;
        $data['receiver_activity_id'] = $this->receiver_activity_id;
        
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])) continue;
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData)))  continue;
            
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
        $data['@receiver_activity_id'] = $this->receiver_activity_id;
        
        return $data;
    }
    
}