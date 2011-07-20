<?php 
class Iati_WEP_Activity_Elements_Transaction_FlowType extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'code', 'xml_lang');
    protected $text;
    protected $code;
    protected $xml_lang;
    protected $options = array();
    
    protected $attributes_html = array(
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'code' => array(
                    'name' => 'ref',
                    'label' => 'Flow Type Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
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
        $this->setOptions();
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        $this->options['code'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('FlowType', null, '1'));
        $this->options['xml_lang'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('Language', null, '1'));
        
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getClassName(){
        return 'FlowType';
    }
    
    public function setAttributes ($data) {
        $this->ref = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->text = $data['text'];
        $this->xml_lang = key_exists('@xml_lang', $data)?$data['@xml_lang']:$data['xml_lang'];
    }
    
    public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
}