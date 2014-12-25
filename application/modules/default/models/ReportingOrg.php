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
        $reportingOrg['id'] = $id; 
        $reporting_org_id = $model->updateRowsToTable($this->_name, $reportingOrg);
        if (!$reporting_org_id)
            $row = $model->getRowById($this->_name, 'id', $reportingOrg['id']);
            $reporting_org_id = $row['id'];
        
        $reportingOrgNarrative['@xml_lang'] = $defaults['reporting_org_lang'];
        $reportingOrgNarrative['text'] = $defaults['reporting_org'];
        $model->updateRow('iati_reporting_org/narrative', $reportingOrgNarrative, 'reporting_org_id', $reporting_org_id);
    }
    
    public function getActivityIdById($id)
    {
        $row = $this->fetchRow(array('id = ?'=>$id));
        return $row->activity_id;
    }
}
