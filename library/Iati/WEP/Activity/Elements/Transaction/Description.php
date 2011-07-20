<?php 
class Iati_WEP_Activity_Elements_Transaction_Description extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'xml_lang');
    protected $text;
    protected $xml_lang;
    protected $options = array();
    
    protected $attributes_html = array(
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'xml_lang' => array(
                    'name' => 'xml_lang',
                    'label' => 'Language',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
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
        $this->options['xml_lang'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('Language', null, '1'));
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getClassName(){
        return 'Description';
    }
    
    public function setAttributes ($data) {
        $this->currency = (key_exists('@xml_lang', $data))?$data['@xml_lang']:$data['xml_lang'];
        $this->text = $data['text'];
    }
    
    public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
}