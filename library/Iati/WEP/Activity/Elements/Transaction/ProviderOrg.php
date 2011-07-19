<?php 
class Iati_WEP_Activity_Elements_Transaction_ProviderOrg extends Iati_WEP_Activity_Elements_Transaction
{
    protected $attributes = array('text', 'ref', 'provider_activity_id');
    protected $text;
    protected $ref;
    protected $provider_activity_id;
    protected $options = array();
    
    protected $attributes_html = array(
                'text' => array(
                    'name' => 'text',
                    'label' => 'Text',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
                ),
                'ref' => array(
                    'name' => 'code',
                    'label' => 'Organisation Identifier Code',
                    'html' => '<select name="%(name)s" %(attrs)s>%(options)s</select>',
                    'options' => '',
                ),
                'provider_activity_id' => array(
                    'name' => 'provider_activity_id',
                    'label' => 'Provider Activity Id',
                    'html' => '<input type="text" name="%(name)s" %(attrs)s value= "%(value)s" />',
                    'attrs' => array('id' => 'id')
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
        $this->options['ref'] = array_merge(array('0' => 'Select anyone'),$model->getCodeArray('OrganisationIdentifier', null, '1'));
    }
    
    public function getOptions($name = NULL)
    {
        return $this->options[$name];
    }
    
    public function getClassName(){
        return 'ProviderOrg';
    }
    
    public function setAttributes ($data) {
        $this->code = (key_exists('@ref', $data))?$data['@ref']:$data['ref'];
        $this->text = $data['text'];
        $this->provider_activity_id = $data['provider_activity_id'];
    }
    
public function getHtmlAttrs()
    {
        return $this->attributes_html;
    }
}