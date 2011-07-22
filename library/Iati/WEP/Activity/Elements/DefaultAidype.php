<?php
class Iati_WEP_Activity_Elements_DefaultAidType extends Iati_WEP_Activity_Elements_ElementBase
{
     protected $attributes = array('code', 'text', 'xml_lang');
     protected $code;
     protected $text;
     protected $xml_lang;
     protected $id = 0;
     
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
        'code' => array(
            'name' => 'code',
            'label' => 'Aid Type Code',
            'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
            'options' => '',
        ),
        'xml_lang' => array(
            'name' => 'xml_lang',
            'label' => 'Language',
            'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
            'options' => '',
        )                   
     );
     
     protected static $count = 0;
     protected $objectId;
     
     public function __construct()
    {
        $this->objectId = self::$count;
        self::$count += 1;
    
        $this->setOptions();
        
        $this->validators = array(
//                        'transaction_id' => 'NotEmpty',
                        'code' => 'NotEmpty',
                    );
        $this->multiple = false;
    }
    
    public function setOptions()
    {
        $model = new Model_Wep();
        
        $select_anyone = array('0' => 'Select anyone');
        $this->options['code'] = array_merge($select_anyone, $model->getCodeArray('AidType', null, '1'));
        $this->options['xml_lang'] = array_merge($select_anyone, $model->getCodeArray('Language', null, '1'));
    }
    
    public function getClassName () {
        return 'DefaultAidType';
    }
    
    public function setAttributes ($data) {
        $this->code = (key_exists('@code', $data))?$data['@code']:$data['code'];
        $this->xml_lang = (key_exists('@xml_lang', $data))? $data['@xml_lang'] : $data['xml_lang'];
        $this->text = $data['text'];
    }
    
    public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    public function validate()
    {
        $data['code'] = $this->code;
        $data['xml_lang'] = $this->xml_lang;
        $data['text'] = $this->text;
        
        parent::validate($data);
    }
}