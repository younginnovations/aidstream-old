<?php 
class Iati_WEP_Activity_Elements_Location_Coordinates extends Iati_WEP_Activity_Elements_Location
{
    protected $attributes = array('id', 'latitude', 'longitude', 'precision');
    protected $latitude;
    protected $longitude;
    protected $precision;
    protected $id = 0;
    protected $options = array();
    protected $className = 'Coordinates';
    
    
    protected $validators = array(
                                'latitude' => 'NotEmpty',
                                'longitude' => 'NotEmpty',
                            );
                            
    protected $attributes_html = array(
                'id' => array(
                    'name' => 'id',
                    'html' => '<input type= "hidden" name="%(name)s" value= "%(value)s" />' 
                ),
                'country' => array(
                    'name' => 'country',
                    'label' => 'Country',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                ),
                'adm1' => array(
                    'name' => 'adm1',
                    'label' => 'Admin-1',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                ),
                
                'adm2' => array(
                    'name' => 'adm2',
                    'label' => 'Admin-2',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
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
        
        $this->options['country'] = $model->getCodeArray('Country', null, '1');
        $this->options['adm1'] = $model->getCodeArray('AdministrativeAreaCode(First-level)', null, '1');
        $this->options['adm2'] = $model->getCodeArray('AdministrativeAreaCode(Second-level)', null, '1');
        
    }
    
    public function setAttributes ($data) {
        $this->id = (isset($data['id']))?$data['id']:0; 
        $this->country = (key_exists('@country', $data))?$data['@country']:$data['country'];
        $this->adm1 = (key_exists('@adm1', $data))?$data['@adm1']:$data['adm1'];
        $this->adm2 = (key_exists('@adm2', $data))?$data['@adm2']:$data['adm2'];
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
        $data['country'] = $this->country;
        $data['adm1'] = $this->adm1;
        $data['adm2'] = $this->adm2;
//        print_r($data);exit;
        foreach($data as $key => $eachData){
            
            if(empty($this->validators[$key])){ continue; }
            
            if(($this->validators[$key] != 'NotEmpty') && (empty($eachData))) {  continue; }
            
            $string = "Zend_Validate_". $this->validators[$key];
            $validator = new $string();
            
            if(!$validator->isValid($eachData)){
//                print "dd";exit;
                $this->error[$key] = $validator->getMessages();
                $this->hasError = true;

            }
        }
    }
    
    public function getCleanedData(){
        $data = array();
        $data ['id'] = $this->id;
        $data['@country'] = $this->country;
        $data['@adm1'] = $this->adm1;
        $data['@adm2'] = $this->adm2;
        
        return $data;
    }
    
    /*public function hasErrors()
    {
        return $this->hasError;
    }*/
    

    
}