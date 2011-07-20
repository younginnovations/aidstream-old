<?php 
class Iati_WEP_Activity_Elements_Transaction_Value extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'currency', 'value_date');
    protected $text;
    protected $currency;
    protected $value_date;
    protected $options = array();
    
    protected $attributes_html = array(
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'currency' => array(
                    'name' => 'currency',
                    'label' => 'Currency',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                ),
                'value_date' => array(
                    'name' => 'value_date',
                    'label' => 'Value Date',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'date', 'class'=>'datepicker'),
                ),
    );
    
    protected static $count = 0;
    protected $objectId;

    public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
    }
    
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['currency'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('Currency', null, '1'));
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getClassName(){
        return 'Value';
    }
    
    public function setAttributes ($data) {
//        print_r($data);exit;
        $this->currency = (key_exists('@currency', $data))?$data['@currency']:$data['currency'];
        $this->text = $data['text'];
        $this->value_date = (key_exists('@value_date', $data))?$data['@value_date']:$data['value_date'];
    }
    
    public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
}