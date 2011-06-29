<?php
class Iati_WEP_Datasets
{
    /*protected $iati_activities = array('@generated-datetime', '@version');
    protected $iati_activity = array('@xml:lang', '@default-currency','hierarchy', '@last-updated-datetime');
    protected $reporting_org = array('@ref', '@type', '@text', '@xml:lang');
    protected $iati_identifier = array('text');
    protected $other_identifier = array('@owner-ref', '@owner-name', 'text');*/
    protected $_data;
    
    
    protected $iati_activities = array('@generated-datetime'=>array('input' =>'TextBox'), '@version' =>array('input' =>  'TextBox'));
    protected $iati_activity = array('@xml:lang' => array('input'=>'Select', 'table'=>'Language'), 
                                    '@default-currency'=> array('input' => 'Select', 'table' =>'Currency'),
                                    '@hierarchy' => array('input'=>'TextBox'), '@last-updated-datetime' => array('input'=>'TextBox'));
    protected $reporting_org = array('@ref' => array('input' => 'Select', 'table'=>'ref'), '@type' => array('input'=>'Select', 'table'=>'OrganisationType'), 
                                    'text' => array('input'=>'TextBox'), 
                                    '@xml:lang'=>array('input' => 'Select', 'table'=>'Language'));
    protected $iati_identifier = array('text'=>array('input'=>'TextArea'));
    protected $other_identifier = array('@owner-ref' =>array('input'=>'Select', 'table'=>'ref'), '@owner-name'=>array('input'=>'TextBox'), 'text'=>array('input'=>'TextArea'));
    protected $activity_title = array('@xml:lang' => array('input'=>'Select', 'table'=>'Language'),
                                      'text'=>array('input'=>'TextArea'),);
    protected $activity_date = array('@type'=>array('input'=>'Select','table'=>'ActivityDataType'),
                                     '@iso-date'=>array('input'=>'Text', 'class'=>'date'), 
                                      'text'=>array('input'=>'Textarea'),
                                     '@xml:lang' => array('input'=>'Select', 'table'=>'Language'),j);
    protected $contact_info = array(
                                'organisation' => array(
                                                    
                                                ),
                                
                            );
    public function setData($name){
        $this->_data[$name] = $this->$name;
    }
    public function getData()
    {
        return  $this->_data;
    }
    
}