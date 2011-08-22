<?php
class Iati_WEP_Activity_Elements_ActivityWebsite extends Iati_WEP_Activity_Elements_ElementBase
{
	protected $attributes = array('id', 'text');
	protected $text;
	protected $xml_lang;
	protected $id = 0;
	protected $options = array();
	protected $validators = array(
                                'text' => 'NotEmpty',
	);
	protected $className = 'ActivityWebsite';
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
                    );

                    protected static $count = 0;
                    protected $objectId;
                    protected $error = array();
                    protected $hasError = false;
                    protected $multiple = true;

                    public function __construct()
                    {
                    	//        $this->checkPrivilege();
                    	parent::__construct();
                    	$this->objectId = self::$count;
                    	self::$count += 1;
                    	$this->setOptions();
                    }

                    public function setOptions()
                    {
                    }

                    public function setAttributes ($data) {
                    	$this->id = (isset($data['id']))?$data['id']:0;
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
                    	$data['text'] = $this->text;

                    	parent :: validate($data);
                    }

                    public function getCleanedData()
                    {
                    	$data = array();
                    	$data ['id'] = $this->id;
                    	$data['text'] = $this->text;

                    	return $data;
                    }

}
