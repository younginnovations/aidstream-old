<?php
class Model_ReportingOrg extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_reporting_org';
    
    public function updateReportingOrg($id)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();

        $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
        $defaults = $defaultFieldsValues->getDefaultFields();
        
        $reportingOrg['@ref'] = $defaults['reporting_org_ref'];
        $reportingOrg['@type'] = $defaults['reporting_org_type'];
        $reportingOrg['@xml_lang'] = $defaults['reporting_org_lang'];
        $reportingOrg['text'] = $defaults['reporting_org'];
        
        $this->update($reportingOrg , array('id = ?'=>$id));
    }
    
    public function getActivityIdById($id)
    {
        $row = $this->fetchRow(array('id = ?'=>$id));
        return $row->activity_id;
    }
}
