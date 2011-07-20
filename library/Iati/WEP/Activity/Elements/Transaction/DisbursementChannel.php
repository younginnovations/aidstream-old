<?php 
class Iati_WEP_Activity_Elements_Transaction_DisbursementChannel extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'code');
    protected $text;
    protected $code;
    protected $options = array();
    
    protected $attributes_html = array(
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'code' => array(
                    'name' => 'code',
                    'label' => 'Disbursement Channel Code',
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
        $this->options['code'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('AidType', null, '1'));
        $this->options['xml_lang'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('Language', null, '1'));
        
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getClassName(){
        return 'DisbursementChannel';
    }
    
    public function setAttributes ($data) {
        $this->ref = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->text = $data['text'];
    }
    
    public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
}